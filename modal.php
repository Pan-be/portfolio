<style>
    .modal {
        visibility: hidden;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        max-width: 90vw;
        max-height: 100vh;
        background: #100e2a;
        box-shadow: 0 5px 50px hsl(0, 0%, 94%);
        padding: 20px;
        border-radius: 10px;
        width: 400px;
        text-align: center;
        position: relative;
        margin: 20px auto;
        display: grid;
        justify-items: center;
        gap: 20px;

        & h2 {
            display: grid;
            justify-items: center;
            gap: 5px;
        }
    }

    .checkbox {
        display: grid;
        justify-items: center;
        gap: 20px;
        grid-template-columns: repeat(3, 1fr);

        &>span {
            margin: 0;
            padding: 0;

        }

        & .wpcf7-list-item {
            display: grid;
            justify-items: center;
            text-align: center;

            &>label {
                display: grid;
                justify-items: center;
                text-align: center;
            }
        }
    }


    .close-modal {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
    }

    #selected-plan {
        text-transform: uppercase;
    }

    .wpcf7-mail-sent-ok {
        color: #3c763d;
    }

    .wpcf7-validation-errors {

        color: #a94442;

    }

    #response {
        display: none;
    }
</style>
<div id="subscription-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2><span id="modal-title"></span> <span id="selected-plan"></span></h2>
        <div id="modal-form-container"></div>
        <span id="response"></span>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ajaxurl = '/wp-admin/admin-ajax.php';
        const modal = document.getElementById("subscription-modal");
        const closeModal = document.querySelector(".close-modal");

        const modalTitle = document.getElementById("modal-title");
        const selectedPlanText = document.querySelector("#selected-plan");
        const modalFormContainer = document.getElementById("modal-form-container");

        const modalPricingTitle = `<?php echo esc_html(get_field('modal_pricing_title')); ?>`;
        const modalSubTitle = `<?php echo esc_html(get_field('modal_subscription_title')); ?>`;
        const formShortcode = `<?php echo do_shortcode(get_field('form_shortcode')); ?>`;
        const pricingPlanShortcode = `<?php echo do_shortcode(get_field('modal_form_pricing_plan_shortcode')); ?>`;

        const responseText = document.getElementById('response');

        document.querySelectorAll(".open-modal").forEach(button => {
            button.addEventListener("click", function() {
                const plan = this.getAttribute("data-plan");

                if (plan) {
                    modalTitle.textContent = modalPricingTitle;
                    selectedPlanText.innerText = plan;
                    modalFormContainer.innerHTML = formShortcode;
                } else {
                    modalTitle.textContent = modalSubTitle;
                    selectedPlanText.textContent = "";
                    modalFormContainer.innerHTML = pricingPlanShortcode;
                }

                setTimeout(() => {
                    const planInput = document.querySelector('[name="your-plan"]');
                    if (planInput && plan) {
                        planInput.value = plan;
                    }

                    // Dodaj obsługę AJAX dla formularza
                    const form = modalFormContainer.querySelector('form');
                    if (form) {
                        // Usuń istniejący listener, jeśli istnieje
                        const oldForm = form.cloneNode(true);
                        form.parentNode.replaceChild(oldForm, form);

                        // The form is injected after page load, so Turnstile's automatic scan never sees it:
                        // render the widget by hand, otherwise CF7 gets no token and rejects the message as spam.
                        let turnstileId = null;
                        const turnstileBox = oldForm.querySelector('.cf-turnstile');
                        if (turnstileBox && window.turnstile) {
                            const d = turnstileBox.dataset;
                            const params = {
                                sitekey: d.sitekey,
                                'response-field-name': d.responseFieldName
                            };
                            ['action', 'appearance', 'size', 'theme', 'language'].forEach(key => {
                                if (d[key]) params[key] = d[key];
                            });
                            turnstileId = turnstile.render(turnstileBox, params);
                        }

                        oldForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const formData = new FormData(this);

                            // Dodaj wszystkie dane CF7
                            formData.append('action', 'cf7_ajax_submit');
                            formData.append('_wpcf7', this.querySelector(
                                '[name="_wpcf7"]').value);
                            formData.append('_wpcf7_unit_tag', this.querySelector(
                                '[name="_wpcf7_unit_tag"]').value);
                            formData.append('_wpcf7_container_post', this
                                .querySelector('[name="_wpcf7_container_post"]')
                                .value);

                            console.log('Dane formularza:', Object.fromEntries(
                                formData));

                            fetch(ajaxurl, {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => {
                                    console.log('Status odpowiedzi:', response
                                        .status);
                                    return response.json();
                                })
                                .then(data => {
                                    console.log('Pełna odpowiedź:', data);

                                    // Znajdź lub stwórz kontener na wiadomości
                                    let responseContainer = this.querySelector(
                                        '.wpcf7-response-output');
                                    if (!responseContainer) {
                                        responseContainer = document
                                            .createElement('div');
                                        responseContainer.className =
                                            'wpcf7-response-output';
                                        this.appendChild(responseContainer);
                                    }

                                    if (data.success) {
                                        // Wiadomość wysłana poprawnie
                                        responseText.textContent = data
                                            .data.message;
                                        responseText.className =
                                            'wpcf7-mail-sent-ok';

                                        responseText.style.display = "block";
                                        // Opcjonalnie: reset formularza
                                        this.reset();

                                        // Zamknij modal po sukcesie
                                        setTimeout(() => {
                                            modal.style.display =
                                                'none';
                                            responseText.style.display =
                                                "none";
                                        }, 2000);
                                    } else {
                                        // Błędy walidacji

                                        responseText.textContent = data
                                            .data.message ||
                                            'Wystąpiły błędy w formularzu';
                                        responseText.className =
                                            'wpcf7-validation-errors';
                                        responseText.style.display = "block";
                                    }
                                })
                                .catch(error => {
                                    console.error('Błąd:', error);

                                    // Dodaj kontener na błędy, jeśli nie istnieje
                                    let responseContainer = this.querySelector(
                                        '.wpcf7-response-output');
                                    if (!responseContainer) {
                                        responseContainer = document
                                            .createElement('div');
                                        responseContainer.className =
                                            'wpcf7-response-output';
                                        this.appendChild(responseContainer);
                                    }

                                    responseContainer.textContent =
                                        'Wystąpił błąd podczas wysyłania formularza';
                                    responseContainer.className =
                                        'wpcf7-response-output wpcf7-validation-errors';
                                })
                                .finally(() => {
                                    // A Turnstile token is single-use: get a fresh one for the next attempt.
                                    if (turnstileId !== null) turnstile.reset(turnstileId);
                                });
                        });
                    }

                    modal.style.visibility = "visible";
                    modal.style.display = "block";
                }, 50);
            });
        });

        closeModal.addEventListener("click", function() {
            modal.style.display = "none";
        });

        window.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
</script>
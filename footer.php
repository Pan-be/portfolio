</main>
<footer class="wrapper">
    <?php
    wp_nav_menu(array(
        'theme_location' => 'bottom-menu',
        'menu_class' => 'bottom__nav-links',
        'container' => null,
        'walker' => new Custom_Nav_Walker()

    ));
    ?>
    <p>Copyright &#169; by Pan.Be <?php echo date('Y'); ?></p>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const copyButtons = document.querySelectorAll('.copy-btn');

        copyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const codeElement = this.closest('div').querySelector('code');
                const textToCopy = codeElement.textContent.trim();

                // Tworzymy tymczasowy element textarea
                const textarea = document.createElement('textarea');
                textarea.value = textToCopy;
                textarea.style.position = 'fixed'; // Poza widokiem
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);

                // Zaznaczamy i kopiujemy tekst
                textarea.select();
                try {
                    document.execCommand('copy');
                    const originalText = this.innerText;
                    this.innerText = '✓';

                    setTimeout(() => {
                        this.innerText = originalText;
                    }, 2000);
                } catch (err) {
                    console.error('Error has occured:', err);
                    alert("Sorry I couldn't copy :(");
                }

                // Usuwamy tymczasowy element
                document.body.removeChild(textarea);
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.querySelector('.carousel-content');
        const items = document.querySelectorAll('.carousel-item');
        const prevButton = document.querySelector('.carousel-prev');
        const nextButton = document.querySelector('.carousel-next');

        if (!carousel || !items.length || !prevButton || !nextButton) return;

        let currentIndex = 0;

        function updateCarousel() {
            carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        function goToPrev() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        }

        function goToNext() {
            if (currentIndex < items.length - 1) {
                currentIndex++;
                updateCarousel();
            }
        }

        prevButton.addEventListener('click', goToPrev);
        nextButton.addEventListener('click', goToNext);

        // Opcjonalnie: Dodaj obsługę klawiszy strzałek
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') goToPrev();
            if (e.key === 'ArrowRight') goToNext();
        });
    });
</script>


<?php wp_footer(); ?>
</body>

</html>
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
    <p>Copyright &#169; by Pan.Be <?php echo date('Y'); ?> · <button type="button" class="consent-settings"><?php echo my_theme_is_polish() ? 'Ustawienia cookies' : 'Cookie settings'; ?></button></p>
</footer>
<?php get_template_part('consent'); ?>
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

<?php wp_footer(); ?>
</body>

</html>
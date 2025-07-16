</main>

<footer>
    <div class="footer-content">
        <div class="footer-section">
            <h3 class="logo">PIXEL <span class="color">PERFECT</span></h3>
            <p>Soluciones innovadoras para el crecimiento de tu negocio</p>
        </div>
        <div class="footer-section">
            <h3>Contacto</h3>
            <p><i class="fas fa-envelope"></i> info@PixelPerfect.com</p>
            <p><i class="fas fa-phone"></i> (123) 456-7890</p>
        </div>
        <div class="footer-section">
            <h3>Enlaces</h3>
            <p><a href="<?php echo $root_path; ?>pages/privacidad.php">Política de Privacidad</a></p>
            <p><a href="<?php echo $root_path; ?>pages/contacto.php">Términos de Uso</a></p>
        </div>
        <div class="footer-section">
            <h3>Síguenos</h3>
            <div class="social-icons">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </div>
    <p class="copyright">&copy; 2024 PixelPerfect. Todos los derechos reservados.</p>
</footer>

<?php
// Mostrar banner de cookies si es necesario
echo showCookieBanner();
?>

<script src="<?php echo $root_path; ?>assets/js/script.js"></script>
</body>
</html>

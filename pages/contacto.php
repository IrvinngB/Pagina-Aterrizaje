<?php
require_once '../includes/functions.php';
$pageTitle = 'Contacto'; // Define el título de la página
include '../includes/header.php'; // Incluye el encabezado común
?>

        <br>
        <section id="contacto">
            <h1>Contáctanos</h1>
            <div class="contact-container">
                <div class="contact-info">
                    <h2>Información de Contacto</h2>
                    <p><i class="fas fa-map-marker-alt"></i>1007 Mountain Drive, Gotham City, Nueva Jersey, EE.UU.</p>
                    <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                    <p><i class="fas fa-envelope"></i> info@PixelPerfect.com</p>
                    <div class="social-icons-contact">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    </div>
                    <p><a href="https://metadesign.com/" class="links" target="_blank">Visitar MetaDesign</a></p>
                </div>
                <form id="contactForm" action="https://formspree.io/f/xvgogbbb" method="POST">
                    <h2>Envíanos un mensaje</h2>
                    <label for="name">Nombre</label>
                    <input type="text" id="name" name="name" placeholder="Nombre" required>
                
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                
                    <label for="message">Mensaje</label>
                    <textarea id="message" name="message" placeholder="Escribe tu mensaje aquí..." required></textarea>
                    
                    <button type="submit" class="cta-button">Enviar <i class="fas fa-paper-plane"></i></button>
                </form>                
            </div>
            <div class="map-container">
                <h2>Nuestra Ubicación</h2>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3021.0619515087624!2d-73.96815832397081!3d40.78265215703158!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2589a018531e3%3A0xb9df1f7387a94119!2sCentral%20Park!5e0!3m2!1ses-419!2spa!4v1726886309896!5m2!1ses-419!2spa" 
                 width="600" height="450" 
                 style="border:0;" 
                 allowfullscreen="" 
                 loading="lazy" 
                 referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            </div>
        </section>

<?php include '../includes/footer.php'; ?>

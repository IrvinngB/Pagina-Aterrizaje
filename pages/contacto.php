<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

$pageTitle = 'Contacto';
$message = '';
$messageType = 'success';

// Procesar formulario de contacto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = sanitizeInput($_POST['nombre']);
    $correo = sanitizeInput($_POST['correo']);
    $telefono = sanitizeInput($_POST['telefono'] ?? '');
    $asunto = sanitizeInput($_POST['asunto'] ?? 'Consulta general');
    $mensaje_texto = sanitizeInput($_POST['mensaje']);
    
    if (empty($nombre) || empty($correo) || empty($mensaje_texto)) {
        $message = 'Por favor, complete todos los campos obligatorios.';
        $messageType = 'danger';
    } elseif (!validateEmail($correo)) {
        $message = 'Por favor, ingrese un correo electrónico válido.';
        $messageType = 'danger';
    } else {
        // Insertar en la base de datos
        $id_usuario = isLoggedIn() ? $_SESSION['user_id'] : null;
        
        $stmt = $conn->prepare("INSERT INTO Contacto (nombre_contacto, correo_contacto, telefono_contacto, asunto, mensaje, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $nombre, $correo, $telefono, $asunto, $mensaje_texto, $id_usuario);
        
        if ($stmt->execute()) {
            $message = 'Mensaje enviado correctamente. Nos pondremos en contacto pronto.';
            $messageType = 'success';
            
            // Limpiar variables para resetear el formulario
            $nombre = $correo = $telefono = $asunto = $mensaje_texto = '';
        } else {
            $message = 'Error al enviar el mensaje. Por favor, intente nuevamente.';
            $messageType = 'danger';
        }
        
        $stmt->close();
    }
}

include '../includes/header.php';
?>

<main class="container-fluid p-0">
        <br>
        <section id="contacto">
            <h1>Contáctanos</h1>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
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
                
                <form id="contactForm" method="POST" action="">
                    <h2>Envíanos un mensaje</h2>
                    
                    <label for="nombre">Nombre *</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" 
                           value="<?php echo htmlspecialchars($nombre ?? ''); ?>" required>
                
                    <label for="correo">Correo Electrónico *</label>
                    <input type="email" id="correo" name="correo" placeholder="correo@ejemplo.com" 
                           value="<?php echo htmlspecialchars($correo ?? ''); ?>" required>
                    
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="(123) 456-7890" 
                           value="<?php echo htmlspecialchars($telefono ?? ''); ?>">
                    
                    <label for="asunto">Asunto</label>
                    <input type="text" id="asunto" name="asunto" placeholder="Asunto del mensaje" 
                           value="<?php echo htmlspecialchars($asunto ?? ''); ?>">
                
                    <label for="mensaje">Mensaje *</label>
                    <textarea id="mensaje" name="mensaje" placeholder="Escribe tu mensaje aquí..." required><?php echo htmlspecialchars($mensaje_texto ?? ''); ?></textarea>
                    
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
</main>

<?php include '../includes/footer.php'; ?>

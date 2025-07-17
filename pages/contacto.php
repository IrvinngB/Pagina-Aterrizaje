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
    <section class="contact-hero">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <h1><i class="fas fa-envelope"></i> Contáctanos</h1>
            <p>Estamos aquí para ayudarte a hacer realidad tu proyecto</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                    <div class="alert-content">
                        <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                        <span><?php echo $message; ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="info-header">
                        <h2>Información de Contacto</h2>
                        <p>Múltiples formas de conectar contigo</p>
                    </div>
                    
                    <div class="info-items">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <h3>Dirección</h3>
                                <p>1007 Mountain Drive<br>Gotham City, Nueva Jersey<br>EE.UU.</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h3>Teléfono</h3>
                                <p>(123) 456-7890</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h3>Email</h3>
                                <p>info@PixelPerfect.com</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h3>Horario</h3>
                                <p>Lun - Vie: 9:00 AM - 6:00 PM<br>Sáb: 10:00 AM - 2:00 PM</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-section">
                        <h3>Síguenos</h3>
                        <div class="social-icons">
                            <a href="#" aria-label="Facebook" class="social-link">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" aria-label="Instagram" class="social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" aria-label="Twitter" class="social-link">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" aria-label="LinkedIn" class="social-link">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="external-link">
                        <a href="https://metadesign.com/" target="_blank" class="link-button">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Visitar MetaDesign</span>
                        </a>
                    </div>
                </div>
                
                <div class="contact-form-container">
                    <form id="contactForm" method="POST" action="" class="contact-form">
                        <div class="form-header">
                            <h2>Envíanos un mensaje</h2>
                            <p>Cuéntanos sobre tu proyecto y te responderemos pronto</p>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nombre">Nombre completo *</label>
                                <input type="text" id="nombre" name="nombre" 
                                       placeholder="Tu nombre completo" 
                                       value="<?php echo htmlspecialchars($nombre ?? ''); ?>" 
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="correo">Correo Electrónico *</label>
                                <input type="email" id="correo" name="correo" 
                                       placeholder="tu@email.com" 
                                       value="<?php echo htmlspecialchars($correo ?? ''); ?>" 
                                       required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="tel" id="telefono" name="telefono" 
                                       placeholder="(123) 456-7890" 
                                       value="<?php echo htmlspecialchars($telefono ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="asunto">Asunto</label>
                                <select id="asunto" name="asunto">
                                    <option value="Consulta general" <?php echo (($asunto ?? '') === 'Consulta general') ? 'selected' : ''; ?>>Consulta general</option>
                                    <option value="Diseño de logotipo" <?php echo (($asunto ?? '') === 'Diseño de logotipo') ? 'selected' : ''; ?>>Diseño de logotipo</option>
                                    <option value="Desarrollo web" <?php echo (($asunto ?? '') === 'Desarrollo web') ? 'selected' : ''; ?>>Desarrollo web</option>
                                    <option value="Branding completo" <?php echo (($asunto ?? '') === 'Branding completo') ? 'selected' : ''; ?>>Branding completo</option>
                                    <option value="Soporte técnico" <?php echo (($asunto ?? '') === 'Soporte técnico') ? 'selected' : ''; ?>>Soporte técnico</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="mensaje">Mensaje *</label>
                            <textarea id="mensaje" name="mensaje" 
                                      placeholder="Cuéntanos sobre tu proyecto, objetivos y cualquier detalle relevante..." 
                                      rows="6" required><?php echo htmlspecialchars($mensaje_texto ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" class="submit-button">
                            <span>Enviar mensaje</span>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <section class="map-section">
        <div class="container">
            <div class="map-header">
                <h2>Nuestra Ubicación</h2>
                <p>Visítanos en nuestras oficinas o programa una videollamada</p>
            </div>
            
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3021.0619515087624!2d-73.96815832397081!3d40.78265215703158!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2589a018531e3%3A0xb9df1f7387a94119!2sCentral%20Park!5e0!3m2!1ses-419!2spa!4v1726886309896!5m2!1ses-419!2spa" 
                        width="100%" height="450" 
                        style="border:0; border-radius: 20px;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>
</main>

<style>
:root {
    --primary-color: #114093;
    --secondary-color: #f2f6f9;
    --accent-color: #e94e1a;
    --text-color: #333;
    --background-color: #ffffff;
    --primary-light: #2a5bb7;
    --primary-dark: #0c2f6e;
    --accent-light: #ff6a3c;
    --accent-dark: #c93f12;
    --secondary-dark: #d1dce8;
}

.contact-hero {
    position: relative;
    height: 50vh;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    text-align: center;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, var(--primary-color), var(--primary-light));
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(17, 64, 147, 0.1);
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    padding: 0 20px;
}

.hero-content h1 {
    font-size: clamp(2.5rem, 5vw, 3.5rem);
    margin-bottom: 1rem;
    font-weight: 700;
}

.hero-content h1 i {
    color: var(--accent-color);
    margin-right: 1rem;
}

.hero-content p {
    font-size: 1.3rem;
    opacity: 0.9;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.contact-section {
    padding: 6rem 0;
    background: white;
}

.alert {
    margin-bottom: 3rem;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-left: 4px solid #28a745;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-left: 4px solid #dc3545;
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.alert-content i {
    font-size: 1.5rem;
}

.alert-success .alert-content i {
    color: #28a745;
}

.alert-danger .alert-content i {
    color: #dc3545;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 4rem;
    align-items: start;
}

.contact-info {
    background: var(--secondary-color);
    padding: 3rem;
    border-radius: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.info-header {
    margin-bottom: 3rem;
}

.info-header h2 {
    color: var(--primary-color);
    font-size: 2rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.info-header p {
    color: var(--text-color);
    opacity: 0.8;
}

.info-items {
    margin-bottom: 3rem;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
}

.info-item:hover {
    transform: translateX(5px);
}

.info-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.info-content h3 {
    color: var(--primary-color);
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.info-content p {
    color: var(--text-color);
    line-height: 1.6;
    margin: 0;
}

.social-section {
    margin-bottom: 2rem;
}

.social-section h3 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 600;
}

.social-icons {
    display: flex;
    gap: 1rem;
}

.social-link {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    text-decoration: none;
}

.social-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(233, 78, 26, 0.3);
    color: white;
}

.link-button {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.link-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(17, 64, 147, 0.3);
    color: white;
}

.contact-form-container {
    background: white;
    padding: 3rem;
    border-radius: 25px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}

.form-header {
    margin-bottom: 2.5rem;
    text-align: center;
}

.form-header h2 {
    color: var(--primary-color);
    font-size: 2rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.form-header p {
    color: var(--text-color);
    opacity: 0.8;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 1rem 1.25rem;
    border: 2px solid var(--secondary-dark);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    color: var(--text-color);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(17, 64, 147, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
}

.submit-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1.25rem 2rem;
    background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(233, 78, 26, 0.3);
}

.submit-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(233, 78, 26, 0.4);
}

.submit-button:active {
    transform: translateY(0);
}

.map-section {
    padding: 6rem 0;
    background: var(--secondary-color);
}

.map-header {
    text-align: center;
    margin-bottom: 3rem;
}

.map-header h2 {
    color: var(--primary-color);
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.map-header p {
    color: var(--text-color);
    font-size: 1.2rem;
    opacity: 0.8;
}

.map-container {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 3rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .contact-info,
    .contact-form-container {
        padding: 2rem;
    }
    
    .info-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .social-icons {
        justify-content: center;
    }
}
</style>

<?php include '../includes/footer.php'; ?>

<?php
require_once 'includes/functions.php';
$pageTitle = 'Inicio'; // Define el título de la página
include 'includes/header.php'; // Incluye el encabezado común
?>
<main class="container-fluid p-0">
        <section id="inicio">
            <div class="video-background">
                <div class="video-foreground">
                    <iframe src="https://www.youtube.com/embed/dQ7Z1YOp9TI?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=dQ7Z1YOp9TI&mute=1&vq=hd720" frameborder="0" allowfullscreen allow="autoplay"></iframe>
                </div>
            </div>
    <div class="content">
        <h1>Perfección pixel a pixel</h1>
        <p>Impulsamos el crecimiento de tu empresa con estrategias personalizadas</p>
        <br>
        <a href="pages/contacto.html" class="cta-button">Solicitar Consulta <i class="fas fa-arrow-right"></i></a>
    </div>
</section>

<section id="destacados">
    <h2>Servicios de Diseño Destacados</h2>
    <div class="service-grid">
        <div class="service">
            <i class="fas fa-palette"></i>
            <h3>Diseño de Logotipos y Branding</h3>
            <p>Creación de identidades visuales impactantes que destacan en cualquier entorno.</p>
        </div>
        <div class="service">
            <i class="fas fa-bullhorn"></i>
            <h3>Diseño Publicitario</h3>
            <p>Desarrollo de flyers, banners y anuncios digitales para atraer a tu público objetivo.</p>
        </div>
        <div class="service">
            <i class="fas fa-laptop-code"></i>
            <h3>Diseño Web y UX/UI</h3>
            <p>Diseño de sitios web y aplicaciones con una experiencia de usuario óptima.</p>
        </div>
    </div>
    <br>
    <a href="pages/servicios.html" class="cta-button secondary">Ver todos los servicios</a>
</section>

<?php include 'includes/footer.php'; ?>

<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Título por defecto si no se proporciona
$pageTitle = isset($pageTitle) ? $pageTitle : 'PixelPerfect';

// Crear ruta base dinámica para los enlaces
$root_path = "";
if (strpos($_SERVER['PHP_SELF'], '/pages/') !== false || 
    strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    $root_path = "../";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title>PixelPerfect - <?php echo $pageTitle; ?></title>
    <link rel="shortcut icon" href="<?php echo $root_path; ?>assets/images/LogoColorFinal.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo $root_path; ?>assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>    <audio id="background-audio" src="<?php echo $root_path; ?>assets/audio/audio.mp3" loop></audio>

    <header>
        <nav>
            <div class="logo">
                <img src="<?php echo $root_path; ?>assets/images/LogoColorFinal.svg" alt="Logo de PixelPerfect" />
                <span class="logo-text">
                    <span class="pixel">Pixel</span><span class="perfect">Perfect</span>
                </span>
            </div>
            <button class="hamburger" aria-label="Abrir menú">
                <span></span>
                <span></span>
                <span></span>
            </button>            <ul class="nav-links">
                <li><a href="<?php echo $root_path; ?>index.php" <?php echo ($pageTitle == 'Inicio') ? 'class="active"' : ''; ?>>Inicio</a></li>
                <li><a href="<?php echo $root_path; ?>pages/servicios.html" <?php echo ($pageTitle == 'Servicios') ? 'class="active"' : ''; ?>>Servicios</a></li>
                <li><a href="<?php echo $root_path; ?>pages/nosotros.html" <?php echo ($pageTitle == 'Nosotros') ? 'class="active"' : ''; ?>>Sobre Nosotros</a></li>
                <li><a href="<?php echo $root_path; ?>pages/contacto.html" <?php echo ($pageTitle == 'Contacto') ? 'class="active"' : ''; ?>>Contacto</a></li>
                <li><button id="mute-button" class="audio-control" aria-label="Controlar audio"><i class="fas fa-volume-mute"></i></button></li>                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Usuario logueado -->
                    <li class="user-menu">
                        <a href="<?php echo $root_path; ?>pages/perfil.php">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuario'); ?>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="<?php echo $root_path; ?>admin/dashboard.php">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo $root_path; ?>pages/logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <!-- Usuario no logueado -->
                    <li><a href="<?php echo $root_path; ?>pages/login.php">Iniciar Sesión</a></li>
                    <li><a href="<?php echo $root_path; ?>pages/registro.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

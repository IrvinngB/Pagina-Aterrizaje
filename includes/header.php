<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Título por defecto si no se proporciona
$pageTitle = isset($pageTitle) ? $pageTitle : 'PixelPerfect';

// Crear ruta base dinámica para los enlaces - MEJORADO
$root_path = "";
$current_path = $_SERVER['PHP_SELF'];

// Mejorar la detección de rutas
if (strpos($current_path, '/admin/') !== false) {
    $root_path = "../";
} elseif (strpos($current_path, '/pages/') !== false) {
    $root_path = "../";
}

// Incluir el sistema de cookies
$cookies_path = ($root_path !== "" ? $root_path : "./") . 'includes/cookies.php';
if (file_exists($cookies_path)) {
    require_once $cookies_path;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelPerfect - <?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="shortcut icon" href="<?php echo $root_path; ?>assets/images/LogoColorFinal.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo $root_path; ?>assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Asegurar que el header sea clickeable */
        header {
            position: relative;
            z-index: 999;
        }
        
        header nav .nav-links a {
            position: relative;
            z-index: 1000;
            pointer-events: auto;
            display: block;
            text-decoration: none;
        }
        
        header .logo a {
            pointer-events: auto;
            z-index: 1000;
        }
        
        /* Prevenir interferencias */
        header * {
            pointer-events: auto;
        }
    </style>
</head>
<body>
    <audio id="background-audio" src="<?php echo $root_path; ?>assets/audio/audio.mp3" loop></audio>

    <header>
        <nav>
            <div class="logo">
                <a href="<?php echo $root_path; ?>index.php" style="text-decoration: none; display: flex; align-items: center;">
                    <img src="<?php echo $root_path; ?>assets/images/LogoColorFinal.svg" alt="Logo de PixelPerfect" />
                    <span class="logo-text">
                        <span class="pixel">Pixel</span><span class="perfect">Perfect</span>
                    </span>
                </a>
            </div>
            <button class="hamburger" aria-label="Abrir menú" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-links" id="nav-links">
                <li><a href="<?php echo $root_path; ?>index.php" <?php echo ($pageTitle == 'Inicio') ? 'class="active"' : ''; ?>>Inicio</a></li>
                <li><a href="<?php echo $root_path; ?>pages/servicios.php" <?php echo ($pageTitle == 'Servicios') ? 'class="active"' : ''; ?>>Servicios</a></li>
                <li><a href="<?php echo $root_path; ?>pages/nosotros.php" <?php echo ($pageTitle == 'Nosotros') ? 'class="active"' : ''; ?>>Sobre Nosotros</a></li>
                <li><a href="<?php echo $root_path; ?>pages/contacto.php" <?php echo ($pageTitle == 'Contacto') ? 'class="active"' : ''; ?>>Contacto</a></li>
                <li><button id="mute-button" class="audio-control" aria-label="Controlar audio"><i class="fas fa-volume-mute"></i></button></li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Usuario logueado -->
                    <li class="user-menu">
                        <a href="<?php echo $root_path; ?>pages/perfil.php">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuario'); ?>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="<?php echo $root_path; ?>admin/dashboard.php">Admin</a></li>
                    <?php elseif (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
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

    <script src="<?php echo $root_path; ?>assets/js/script.js"></script>
    <script>
        function toggleMenu() {
            const navLinks = document.getElementById('nav-links');
            const hamburger = document.querySelector('.hamburger');
            if (navLinks && hamburger) {
                navLinks.classList.toggle('active');
                hamburger.classList.toggle('active');
            }
        }
        
        // Control de audio mejorado
        document.addEventListener('DOMContentLoaded', function() {
            const audioElement = document.getElementById('background-audio');
            const muteButton = document.getElementById('mute-button');
            const muteIcon = muteButton ? muteButton.querySelector('i') : null;

            if (audioElement && muteButton && muteIcon) {
                // Verificar cookie de audio
                const audioPreference = getCookie('audioEnabled');
                if (audioPreference === 'true') {
                    audioElement.muted = false;
                    muteIcon.className = 'fas fa-volume-up';
                } else {
                    audioElement.muted = true;
                    muteIcon.className = 'fas fa-volume-mute';
                }

                muteButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    audioElement.muted = !audioElement.muted;
                    
                    if (audioElement.muted) {
                        muteIcon.className = 'fas fa-volume-mute';
                        setCookie('audioEnabled', 'false', 30);
                    } else {
                        muteIcon.className = 'fas fa-volume-up';
                        setCookie('audioEnabled', 'true', 30);
                        audioElement.play().catch(error => {
                            console.log('Error al reproducir audio:', error);
                        });
                    }
                });
            }

            // Asegurar que todos los enlaces del header funcionen
            const headerLinks = document.querySelectorAll('header nav a');
            headerLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Verificar que el enlace sea válido
                    if (this.href && this.href !== '#') {
                        // Permitir navegación normal
                        return true;
                    }
                });
            });
        });
        
        // Funciones para manejar cookies
        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }
        
        function getCookie(name) {
            let nameEQ = name + "=";
            let ca = document.cookie.split(';');
            for(let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        // Debug function - remover en producción
        function debugHeader() {
            console.log('Current path:', '<?php echo $current_path; ?>');
            console.log('Root path:', '<?php echo $root_path; ?>');
            console.log('Page title:', '<?php echo $pageTitle; ?>');
        }
        
        // Llamar debug al cargar (remover en producción)
        // debugHeader();
    </script>
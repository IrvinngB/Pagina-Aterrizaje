<?php
require_once '../includes/functions.php';
require_once '../includes/cookies.php';
$pageTitle = 'Política de Privacidad';
include '../includes/header.php';
?>

<main class="container-fluid p-0">
    <section id="privacidad">
        <div class="privacy-container">
            <h1><i class="fas fa-shield-alt"></i> Política de Privacidad y Cookies</h1>
            
            <div class="privacy-section">
                <h2>1. Información que Recopilamos</h2>
                <p>En PixelPerfect recopilamos la siguiente información:</p>
                <ul>
                    <li><strong>Información Personal:</strong> Nombre, correo electrónico, teléfono cuando te registras o contactas con nosotros.</li>
                    <li><strong>Información de Uso:</strong> Cómo interactúas con nuestro sitio web.</li>
                    <li><strong>Información Técnica:</strong> Dirección IP, tipo de navegador, sistema operativo.</li>
                </ul>
            </div>

            <div class="privacy-section">
                <h2>2. Uso de Cookies</h2>
                <p>Utilizamos diferentes tipos de cookies para mejorar tu experiencia:</p>
                
                <div class="cookie-types">
                    <div class="cookie-type">
                        <h3><i class="fas fa-cog"></i> Cookies Esenciales</h3>
                        <p>Necesarias para el funcionamiento básico del sitio. No se pueden desactivar.</p>
                        <ul>
                            <li>Gestión de sesiones de usuario</li>
                            <li>Configuración de seguridad</li>
                            <li>Preferencias de idioma</li>
                        </ul>
                    </div>
                    
                    <div class="cookie-type">
                        <h3><i class="fas fa-chart-line"></i> Cookies de Rendimiento</h3>
                        <p>Nos ayudan a entender cómo los visitantes interactúan con nuestro sitio.</p>
                        <ul>
                            <li>Análisis de tráfico</li>
                            <li>Páginas más visitadas</li>
                            <li>Tiempo de permanencia</li>
                        </ul>
                    </div>
                    
                    <div class="cookie-type">
                        <h3><i class="fas fa-user-cog"></i> Cookies de Funcionalidad</h3>
                        <p>Permiten recordar tus preferencias y personalizar tu experiencia.</p>
                        <ul>
                            <li>Preferencias de audio</li>
                            <li>Datos de formularios</li>
                            <li>Configuración de tema</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="privacy-section">
                <h2>3. Gestión de Cookies</h2>
                <p>Puedes controlar tus preferencias de cookies:</p>
                
                <div class="cookie-controls">
                    <div class="cookie-control">
                        <label for="essential-cookies">
                            <input type="checkbox" id="essential-cookies" checked disabled>
                            <span class="checkmark"></span>
                            Cookies Esenciales (Obligatorias)
                        </label>
                    </div>
                    
                    <div class="cookie-control">
                        <label for="performance-cookies">
                            <input type="checkbox" id="performance-cookies" <?php echo CookieManager::get('performance_cookies', 'false') === 'true' ? 'checked' : ''; ?>>
                            <span class="checkmark"></span>
                            Cookies de Rendimiento
                        </label>
                    </div>
                    
                    <div class="cookie-control">
                        <label for="functionality-cookies">
                            <input type="checkbox" id="functionality-cookies" <?php echo CookieManager::get('functionality_cookies', 'false') === 'true' ? 'checked' : ''; ?>>
                            <span class="checkmark"></span>
                            Cookies de Funcionalidad
                        </label>
                    </div>
                </div>
                
                <div class="cookie-actions">
                    <button onclick="saveCookiePreferences()" class="cta-button">Guardar Preferencias</button>
                    <button onclick="clearAllCookies()" class="cta-button secondary">Eliminar Todas las Cookies</button>
                </div>
            </div>

            <div class="privacy-section">
                <h2>4. Tus Derechos</h2>
                <p>Tienes derecho a:</p>
                <ul>
                    <li>Acceder a tu información personal</li>
                    <li>Rectificar datos incorrectos</li>
                    <li>Eliminar tu información</li>
                    <li>Portabilidad de datos</li>
                    <li>Oponerte al procesamiento</li>
                </ul>
            </div>

            <div class="privacy-section">
                <h2>5. Contacto</h2>
                <p>Si tienes preguntas sobre esta política, contacta con nosotros:</p>
                <p><i class="fas fa-envelope"></i> privacidad@pixelperfect.com</p>
                <p><i class="fas fa-phone"></i> (123) 456-7890</p>
            </div>

            <div class="privacy-section">
                <h2>6. Cookies Actuales</h2>
                <div class="current-cookies">
                    <h3>Cookies Activas en tu Navegador:</h3>
                    <div id="cookie-list">
                        <?php
                        $appCookies = CookieManager::getAllAppCookies();
                        if (!empty($appCookies)) {
                            echo '<table class="cookie-table">';
                            echo '<thead><tr><th>Nombre</th><th>Valor</th><th>Acción</th></tr></thead>';
                            echo '<tbody>';
                            foreach ($appCookies as $name => $value) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($name) . '</td>';
                                echo '<td>' . htmlspecialchars(substr($value, 0, 50)) . (strlen($value) > 50 ? '...' : '') . '</td>';
                                echo '<td><button onclick="deleteCookie(\'' . htmlspecialchars($name) . '\')" class="btn-delete">Eliminar</button></td>';
                                echo '</tr>';
                            }
                            echo '</tbody></table>';
                        } else {
                            echo '<p>No hay cookies de aplicación activas.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
function saveCookiePreferences() {
    const performance = document.getElementById('performance-cookies').checked;
    const functionality = document.getElementById('functionality-cookies').checked;
    
    fetch('set_cookies.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=preferences&performance=${performance}&functionality=${functionality}`
    })
    .then(response => response.json())
    .then(data => {
        alert('Preferencias guardadas correctamente');
        location.reload();
    });
}

function clearAllCookies() {
    if (confirm('¿Estás seguro de que quieres eliminar todas las cookies? Esto cerrará tu sesión.')) {
        fetch('set_cookies.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=clear_all'
        })
        .then(() => {
            alert('Cookies eliminadas');
            location.reload();
        });
    }
}

function deleteCookie(name) {
    if (confirm(`¿Eliminar la cookie "${name}"?`)) {
        fetch('set_cookies.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=delete&cookie=${encodeURIComponent(name)}`
        })
        .then(() => {
            location.reload();
        });
    }
}
</script>

<?php include '../includes/footer.php'; ?>

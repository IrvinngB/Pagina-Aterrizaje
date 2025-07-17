<?php
/**
 * Sistema de gestión de cookies para PixelPerfect
 */

class CookieManager {
    
    /**
     * Establecer una cookie
     */
    public static function set($name, $value, $days = 30, $path = '/', $secure = false, $httponly = true) {
        $expires = time() + ($days * 24 * 60 * 60);
        setcookie($name, $value, $expires, $path, '', $secure, $httponly);
        $_COOKIE[$name] = $value; // Para acceso inmediato
    }
    
    /**
     * Obtener una cookie
     */
    public static function get($name, $default = null) {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
    }
    
    /**
     * Verificar si existe una cookie
     */
    public static function exists($name) {
        return isset($_COOKIE[$name]);
    }
    
    /**
     * Eliminar una cookie
     */
    public static function delete($name, $path = '/') {
        setcookie($name, '', time() - 3600, $path);
        unset($_COOKIE[$name]);
    }
    
    /**
     * Establecer preferencias del usuario
     */
    public static function setUserPreferences($userId, $preferences) {
        $preferencesJson = json_encode($preferences);
        self::set("user_preferences_$userId", $preferencesJson, 365);
    }
    
    /**
     * Obtener preferencias del usuario
     */
    public static function getUserPreferences($userId) {
        $preferencesJson = self::get("user_preferences_$userId");
        return $preferencesJson ? json_decode($preferencesJson, true) : [];
    }
    
    /**
     * Establecer configuración de privacidad
     */
    public static function setPrivacyConsent($consent = true) {
        self::set('privacy_consent', $consent ? 'true' : 'false', 365);
        self::set('consent_date', date('Y-m-d H:i:s'), 365);
    }
    
    /**
     * Verificar consentimiento de privacidad
     */
    public static function hasPrivacyConsent() {
        return self::get('privacy_consent') === 'true';
    }
    
    /**
     * Recordar datos del último formulario de contacto
     */
    public static function rememberContactForm($data) {
        $allowedFields = ['nombre_contacto', 'correo_contacto', 'telefono_contacto'];
        $rememberedData = [];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $rememberedData[$field] = $data[$field];
            }
        }
        
        self::set('last_contact_form', json_encode($rememberedData), 7);
    }
    
    /**
     * Obtener datos recordados del formulario de contacto
     */
    public static function getRememberedContactForm() {
        $data = self::get('last_contact_form');
        return $data ? json_decode($data, true) : [];
    }
    
    /**
     * Establecer tema preferido
     */
    public static function setTheme($theme) {
        self::set('preferred_theme', $theme, 365);
    }
    
    /**
     * Obtener tema preferido
     */
    public static function getTheme() {
        return self::get('preferred_theme', 'light');
    }
    
    /**
     * Obtener todas las cookies relacionadas con la aplicación
     */
    public static function getAllAppCookies() {
        $appCookies = [];
        $appPrefixes = ['pixelperfect_', 'user_preferences_', 'privacy_', 'consent_', 'last_', 'preferred_', 'audioEnabled'];
        
        foreach ($_COOKIE as $name => $value) {
            foreach ($appPrefixes as $prefix) {
                if (strpos($name, $prefix) === 0 || $name === 'audioEnabled') {
                    $appCookies[$name] = $value;
                    break;
                }
            }
        }
        
        return $appCookies;
    }
    
    /**
     * Limpiar todas las cookies de la aplicación
     */
    public static function clearAllAppCookies() {
        $appCookies = self::getAllAppCookies();
        foreach (array_keys($appCookies) as $cookieName) {
            self::delete($cookieName);
        }
    }
}

/**
 * Mostrar banner de cookies si es necesario
 */
function showCookieBanner() {
    if (!CookieManager::hasPrivacyConsent()) {
        return '
        <div id="cookie-banner" class="cookie-banner">
            <div class="cookie-content">
                <div class="cookie-text">
                    <h4><i class="fas fa-cookie-bite"></i> Uso de Cookies</h4>
                    <p>Utilizamos cookies para mejorar tu experiencia, recordar tus preferencias y analizar el tráfico. Al continuar navegando, aceptas nuestro uso de cookies.</p>
                </div>
                <div class="cookie-actions">
                    <button onclick="acceptCookies()" class="cta-button">Aceptar</button>
                    <button onclick="rejectCookies()" class="cta-button secondary">Rechazar</button>
                    <a href="'.($_SERVER['REQUEST_URI'] ?? '').'/pages/privacidad.php" class="cookie-link">Más información</a>
                </div>
            </div>
        </div>
        <script>
            function acceptCookies() {
                fetch("'.($_SERVER['REQUEST_URI'] ?? '').'/pages/set_cookies.php", {
                    method: "POST",
                    headers: {"Content-Type": "application/x-www-form-urlencoded"},
                    body: "action=accept"
                }).then(() => {
                    document.getElementById("cookie-banner").style.display = "none";
                });
            }
            
            function rejectCookies() {
                fetch("'.($_SERVER['REQUEST_URI'] ?? '').'/pages/set_cookies.php", {
                    method: "POST",
                    headers: {"Content-Type": "application/x-www-form-urlencoded"},
                    body: "action=reject"
                }).then(() => {
                    document.getElementById("cookie-banner").style.display = "none";
                });
            }
        </script>';
    }
    return '';
}
?>

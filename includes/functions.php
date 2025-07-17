<?php
session_start();

// Configurar cookies de sesión
if (!isset($_COOKIE['user_preferences']) && !isLoggedIn()) {
    setcookie('user_preferences', json_encode(['theme' => 'default', 'language' => 'es']), time() + (86400 * 30), "/");
}

// Función para verificar si el usuario está autenticado
function isLoggedIn() {
    return isset($_SESSION['user_id']) || isset($_COOKIE['remember_user']);
}

// Función para verificar si el usuario es administrador
function isAdmin() {
    if (!isLoggedIn()) return false;
    
    // Verificar diferentes formas de ser admin
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) return true;
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') return true;
    
    // Verificar si es el email de admin por defecto
    if (isset($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@pixelperfect.com') return true;
    
    return false;
}

// Función para redirigir al usuario si no está autenticado
function requireLogin() {
    if (!isLoggedIn()) {
        $currentPath = $_SERVER['PHP_SELF'];
        if (strpos($currentPath, '/admin/') !== false) {
            header('Location: ../pages/login.php');
        } else {
            header('Location: login.php');
        }
        exit;
    }
}

// Función para redirigir al usuario si no es administrador
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        $currentPath = $_SERVER['PHP_SELF'];
        if (strpos($currentPath, '/admin/') !== false) {
            header('Location: ../index.php');
        } else {
            header('Location: index.php');
        }
        exit;
    }
}

// Función para validar el correo electrónico
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Verificar si un usuario es parte de la empresa
function isCompanyEmail($email) {
    $domain = substr(strrchr($email, "@"), 1);
    return $domain === 'pixel.com';
}

// Función para sanitizar entradas
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para mostrar mensajes de alerta
function showAlert($message, $type = 'success') {
    return "<div class='alert alert-{$type}' role='alert'>{$message}</div>";
}

// Función para recordar usuario con cookies
function rememberUser($user_id, $email) {
    $token = bin2hex(random_bytes(32));
    setcookie('remember_user', $token, time() + (86400 * 30), "/");
    // Aquí deberías guardar el token en la BD para mayor seguridad
}

// Función para olvidar usuario
function forgetUser() {
    setcookie('remember_user', '', time() - 3600, "/");
}

// Función para establecer configuración de usuario
function setUserPreference($key, $value) {
    $preferences = isset($_COOKIE['user_preferences']) ? json_decode($_COOKIE['user_preferences'], true) : [];
    $preferences[$key] = $value;
    setcookie('user_preferences', json_encode($preferences), time() + (86400 * 30), "/");
}

// Función para obtener configuración de usuario
function getUserPreference($key, $default = null) {
    $preferences = isset($_COOKIE['user_preferences']) ? json_decode($_COOKIE['user_preferences'], true) : [];
    return isset($preferences[$key]) ? $preferences[$key] : $default;
}
?>

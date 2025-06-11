<?php
session_start();

// Función para verificar si el usuario está autenticado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Función para verificar si el usuario es administrador
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Función para redirigir al usuario si no está autenticado
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /Laboratorio 3/pages/login.php');
        exit;
    }
}

// Función para redirigir al usuario si no es administrador
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: /Laboratorio 3/index.php');
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
?>

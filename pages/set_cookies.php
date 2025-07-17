<?php
require_once '../includes/cookies.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'accept':
            CookieManager::setPrivacyConsent(true);
            CookieManager::set('performance_cookies', 'true', 365);
            CookieManager::set('functionality_cookies', 'true', 365);
            echo json_encode(['status' => 'success', 'message' => 'Cookies aceptadas']);
            break;
            
        case 'reject':
            CookieManager::setPrivacyConsent(false);
            CookieManager::set('performance_cookies', 'false', 365);
            CookieManager::set('functionality_cookies', 'false', 365);
            echo json_encode(['status' => 'success', 'message' => 'Cookies rechazadas']);
            break;
            
        case 'preferences':
            $performance = $_POST['performance'] === 'true';
            $functionality = $_POST['functionality'] === 'true';
            
            CookieManager::set('performance_cookies', $performance ? 'true' : 'false', 365);
            CookieManager::set('functionality_cookies', $functionality ? 'true' : 'false', 365);
            
            echo json_encode(['status' => 'success', 'message' => 'Preferencias guardadas']);
            break;
            
        case 'clear_all':
            CookieManager::clearAllAppCookies();
            session_destroy();
            echo json_encode(['status' => 'success', 'message' => 'Cookies eliminadas']);
            break;
            
        case 'delete':
            $cookieName = $_POST['cookie'] ?? '';
            if ($cookieName) {
                CookieManager::delete($cookieName);
                echo json_encode(['status' => 'success', 'message' => 'Cookie eliminada']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Nombre de cookie no válido']);
            }
            break;
            
        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
    }
} else {
    header('Location: ../index.php');
}
?>

<?php
require_once '../includes/functions.php';
requireLogin();

$error = '';
$success = '';

// Obtener información del usuario
include_once '../config/database.php';
$user_id = $_SESSION['user_id'];

// Procesar actualización de perfil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = sanitizeInput($_POST['nombre']);
    $telefono = sanitizeInput($_POST['telefono']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verificar si quiere cambiar la contraseña
    if (!empty($current_password)) {
        // Validar contraseña actual
        $stmt = $conn->prepare("SELECT pass_usuario FROM Usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (password_verify($current_password, $user['pass_usuario'])) {
            if ($new_password === $confirm_password && strlen($new_password) >= 8) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE Usuarios SET nombre_completo = ?, telefono = ?, pass_usuario = ? WHERE id_usuario = ?");
                $stmt->bind_param("sssi", $nombre, $telefono, $hashed_password, $user_id);
            } else {
                $error = 'Las nuevas contraseñas no coinciden o son muy cortas (mínimo 8 caracteres)';
            }
        } else {
            $error = 'La contraseña actual es incorrecta';
        }
    } else {
        // Solo actualizar información personal
        $stmt = $conn->prepare("UPDATE Usuarios SET nombre_completo = ?, telefono = ? WHERE id_usuario = ?");
        $stmt->bind_param("ssi", $nombre, $telefono, $user_id);
    }
    
    if (empty($error)) {
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $nombre; // Actualizar nombre en la sesión
            $success = 'Perfil actualizado con éxito';
        } else {
            $error = 'Error al actualizar el perfil: ' . $conn->error;
        }
    }
}

// Obtener información actualizada del usuario
$stmt = $conn->prepare("SELECT correo_usuario, nombre_completo, telefono, fecha_registro FROM Usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$conn->close();

// Incluimos el header con el título de la página
$pageTitle = 'Mi Perfil';
include '../includes/header.php';
?>

<main class="container">
    <section class="profile-banner">
        <h1>Mi Perfil</h1>
    </section>

    <div class="profile-container">
        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2><?php echo htmlspecialchars($user['nombre_completo']); ?></h2>
                    <p class="profile-email"><?php echo htmlspecialchars($user['correo_usuario']); ?></p>
                    <?php if (isAdmin()): ?>
                        <span class="profile-badge admin">Administrador</span>
                    <?php else: ?>
                        <span class="profile-badge user">Usuario</span>
                    <?php endif; ?>
                </div>

                <div class="profile-details">
                    <div class="profile-item">
                        <i class="fas fa-phone"></i>
                        <div class="profile-info">
                            <span class="label">Teléfono</span>
                            <span><?php echo !empty($user['telefono']) ? htmlspecialchars($user['telefono']) : 'No especificado'; ?></span>
                        </div>
                    </div>
                    <div class="profile-item">
                        <i class="fas fa-calendar"></i>
                        <div class="profile-info">
                            <span class="label">Miembro desde</span>
                            <span><?php echo date('d/m/Y', strtotime($user['fecha_registro'])); ?></span>
                        </div>
                    </div>
                </div>

                <div class="profile-menu">
                    <a href="/Laboratorio 3/pages/mis_solicitudes.php" class="menu-link">
                        <i class="fas fa-clipboard-list"></i> Mis Solicitudes
                    </a>
                    <a href="/Laboratorio 3/pages/historial.php" class="menu-link">
                        <i class="fas fa-history"></i> Historial de Servicios
                    </a>
                    <?php if (isAdmin()): ?>
                        <a href="/Laboratorio 3/admin/dashboard.php" class="menu-link">
                            <i class="fas fa-tachometer-alt"></i> Panel de Administración
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="profile-main">
            <div class="edit-profile">
                <h3>Editar Perfil</h3>

                <?php if (!empty($error)): ?>
                    <div class="alert error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert success">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" id="correo" value="<?php echo htmlspecialchars($user['correo_usuario']); ?>" readonly>
                        <span class="form-hint">El correo electrónico no se puede cambiar</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre_completo']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user['telefono']); ?>">
                    </div>
                    
                    <div class="password-section">
                        <h4><i class="fas fa-lock"></i> Cambiar Contraseña</h4>
                        
                        <div class="form-group">
                            <label for="current_password">Contraseña Actual</label>
                            <input type="password" id="current_password" name="current_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">Nueva Contraseña</label>
                            <input type="password" id="new_password" name="new_password">
                            <span class="form-hint">Dejar en blanco si no deseas cambiar la contraseña (mínimo 8 caracteres)</span>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirmar Nueva Contraseña</label>
                            <input type="password" id="confirm_password" name="confirm_password">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="cta-button">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<style>
    .profile-banner {
        background-color: var(--primary-color);
        padding: 2rem 0;
        text-align: center;
        margin-bottom: 2rem;
    }

    .profile-banner h1 {
        color: white;
        margin: 0;
        font-size: 2.2rem;
    }

    .profile-container {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .profile-sidebar {
        margin-bottom: 2rem;
    }

    .profile-card {
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        background-color: var(--primary-color);
        color: white;
        padding: 2rem 1rem;
        text-align: center;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 3rem;
        color: var(--primary-color);
        border: 4px solid var(--accent-color);
    }

    .profile-header h2 {
        margin: 0 0 0.5rem;
        font-size: 1.5rem;
    }

    .profile-email {
        opacity: 0.8;
        margin: 0 0 1rem;
        font-size: 0.9rem;
    }

    .profile-badge {
        display: inline-block;
        padding: 0.3rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .profile-badge.admin {
        background-color: var(--primary-dark);
        color: white;
    }

    .profile-badge.user {
        background-color: var(--accent-color);
        color: white;
    }

    .profile-details {
        padding: 1.5rem;
    }

    .profile-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }

    .profile-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .profile-item i {
        font-size: 1.2rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--secondary-color);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .profile-info {
        flex: 1;
    }

    .profile-info .label {
        display: block;
        font-weight: bold;
        font-size: 0.9rem;
        color: #777;
        margin-bottom: 0.25rem;
    }

    .profile-menu {
        padding: 1.5rem;
        background-color: var(--secondary-color);
    }

    .menu-link {
        display: block;
        padding: 0.8rem 1rem;
        color: var(--text-color);
        border-radius: 5px;
        margin-bottom: 0.5rem;
        text-decoration: none;
        transition: all 0.3s;
    }

    .menu-link:hover {
        background-color: white;
        color: var(--primary-color);
    }

    .menu-link i {
        margin-right: 0.5rem;
        color: var(--primary-color);
    }

    .edit-profile {
        background-color: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .edit-profile h3 {
        margin-top: 0;
        margin-bottom: 1.5rem;
        color: var(--primary-color);
        font-size: 1.8rem;
        position: relative;
        padding-bottom: 0.5rem;
    }

    .edit-profile h3:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: var(--accent-color);
    }

    .alert {
        padding: 1rem;
        border-radius: 5px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .alert i {
        margin-right: 0.5rem;
        font-size: 1.2rem;
    }

    .alert.error {
        background-color: #ffebee;
        color: #d32f2f;
        border-left: 4px solid #d32f2f;
    }

    .alert.success {
        background-color: #e8f5e9;
        color: #2e7d32;
        border-left: 4px solid #2e7d32;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-color);
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 0.75rem;
        font-size: 1rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
        border-color: var(--accent-color);
        outline: none;
        box-shadow: 0 0 0 2px rgba(233, 78, 26, 0.2);
    }

    .form-hint {
        display: block;
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .password-section {
        background-color: var(--secondary-color);
        padding: 1.5rem;
        border-radius: 8px;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .password-section h4 {
        color: var(--primary-color);
        margin-top: 0;
        margin-bottom: 1.5rem;
    }

    .password-section h4 i {
        margin-right: 0.5rem;
    }

    .form-actions {
        text-align: right;
    }

    .cta-button {
        display: inline-block;
        background-color: var(--accent-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 5px;
        text-decoration: none;
        border: none;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .cta-button:hover {
        background-color: var(--accent-dark);
        color: white;
    }

    .cta-button i {
        margin-right: 0.5rem;
    }

    @media (max-width: 768px) {
        .profile-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php include '../includes/footer.php'; ?>

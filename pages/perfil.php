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

// Incluir el encabezado
$pageTitle = 'Mi Perfil';
include '../includes/header.php';
?>

<div class="container">
    <div class="row">        <div class="col-md-4">
            <div class="card shadow-sm mb-4" style="border-color: var(--primary-color);">
                <div class="card-body text-center">
                    <h3 style="color: var(--primary-color);"><?php echo htmlspecialchars($user['nombre_completo']); ?></h3>
                    <p class="text-muted"><?php echo htmlspecialchars($user['correo_usuario']); ?></p>
                    <p>
                        <i class="fas fa-phone"></i> <?php echo !empty($user['telefono']) ? htmlspecialchars($user['telefono']) : 'No especificado'; ?>
                    </p>
                    <p>
                        <small>Miembro desde: <?php echo date('d/m/Y', strtotime($user['fecha_registro'])); ?></small>
                    </p>                    <?php if (isAdmin()): ?>
                        <div class="badge mb-3" style="background-color: var(--primary-color);">Administrador</div>
                    <?php else: ?>
                        <div class="badge mb-3" style="background-color: var(--accent-color);">Usuario</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="list-group mb-4">
                <a href="/Laboratorio 3/pages/mis_solicitudes.php" class="list-group-item list-group-item-action">Mis Solicitudes</a>
                <a href="/Laboratorio 3/pages/historial.php" class="list-group-item list-group-item-action">Historial de Servicios</a>
                <?php if (isAdmin()): ?>
                    <a href="/Laboratorio 3/admin/dashboard.php" class="list-group-item list-group-item-action">Panel de Administración</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="card-title mb-0">Editar Perfil</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" value="<?php echo htmlspecialchars($user['correo_usuario']); ?>" readonly>
                            <small class="text-muted">El correo electrónico no se puede cambiar</small>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre_completo']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user['telefono']); ?>">
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5>Cambiar Contraseña</h5>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                            <div class="form-text">Dejar en blanco si no deseas cambiar la contraseña</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                          <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn" style="background-color: var(--accent-color); color: white; font-weight: bold;">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

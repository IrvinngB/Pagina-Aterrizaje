<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

requireAdmin();
$pageTitle = 'Gestión de Usuarios';

$message = '';
$messageType = 'success';

// Procesar acciones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'edit') {
        $id = intval($_POST['id_usuario']);
        $correo = sanitizeInput($_POST['correo_usuario']);
        $nombre = sanitizeInput($_POST['nombre_completo']);
        $telefono = sanitizeInput($_POST['telefono']);
        $estado = sanitizeInput($_POST['estado_usuario']);
        
        if (!empty($correo) && !empty($nombre) && $id > 0) {
            $stmt = $conn->prepare("UPDATE Usuarios SET correo_usuario = ?, nombre_completo = ?, telefono = ?, estado_usuario = ? WHERE id_usuario = ?");
            $stmt->bind_param("ssssi", $correo, $nombre, $telefono, $estado, $id);
            
            if ($stmt->execute()) {
                $message = 'Usuario actualizado correctamente.';
            } else {
                $message = 'Error al actualizar el usuario.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    } elseif ($action == 'delete') {
        $id = intval($_POST['id_usuario']);
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE Usuarios SET estado_usuario = 'suspendido' WHERE id_usuario = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $message = 'Usuario suspendido correctamente.';
            } else {
                $message = 'Error al suspender el usuario.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    } elseif ($action == 'activate') {
        $id = intval($_POST['id_usuario']);
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE Usuarios SET estado_usuario = 'activo' WHERE id_usuario = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $message = 'Usuario activado correctamente.';
            } else {
                $message = 'Error al activar el usuario.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    }
}

// Obtener estadísticas de usuarios
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN estado_usuario = 'activo' THEN 1 ELSE 0 END) as activos,
    SUM(CASE WHEN estado_usuario = 'inactivo' THEN 1 ELSE 0 END) as inactivos,
    SUM(CASE WHEN estado_usuario = 'suspendido' THEN 1 ELSE 0 END) as suspendidos
    FROM Usuarios";
$stats = $conn->query($stats_query)->fetch_assoc();

// Obtener usuarios
$usuarios_result = $conn->query("SELECT * FROM Usuarios ORDER BY fecha_registro DESC");

include '../includes/header.php';
?>

<main class="container-fluid p-0">
    <section class="admin-section">
        <div class="admin-header">
            <h1><i class="fas fa-users"></i> Gestión de Usuarios</h1>
            <p>Administrar los usuarios registrados en el sistema</p>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['total']; ?></h3>
                    <p>Total Usuarios</p>
                </div>
            </div>
            
            <div class="stat-card active">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['activos']; ?></h3>
                    <p>Usuarios Activos</p>
                </div>
            </div>
            
            <div class="stat-card inactive">
                <div class="stat-icon">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['inactivos']; ?></h3>
                    <p>Usuarios Inactivos</p>
                </div>
            </div>
            
            <div class="stat-card suspended">
                <div class="stat-icon">
                    <i class="fas fa-user-slash"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['suspendidos']; ?></h3>
                    <p>Usuarios Suspendidos</p>
                </div>
            </div>
        </div>
        
        <!-- Lista de usuarios -->
        <div class="admin-card">
            <h3>Lista de Usuarios</h3>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Fecha Registro</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($usuarios_result && $usuarios_result->num_rows > 0): ?>
                            <?php while($usuario = $usuarios_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $usuario['id_usuario']; ?></td>
                                    <td><?php echo htmlspecialchars($usuario['nombre_completo'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['correo_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['telefono'] ?? 'N/A'); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($usuario['fecha_registro'])); ?></td>
                                    <td>
                                        <span class="status <?php echo $usuario['estado_usuario'] === 'activo' ? 'active' : ($usuario['estado_usuario'] === 'suspendido' ? 'suspended' : 'inactive'); ?>">
                                            <?php echo ucfirst($usuario['estado_usuario']); ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <button class="btn-edit" onclick="editUser(<?php echo htmlspecialchars(json_encode($usuario)); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <?php if ($usuario['estado_usuario'] !== 'suspendido'): ?>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de suspender este usuario?')">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                                <button type="submit" class="btn-delete">
                                                    <i class="fas fa-user-slash"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de activar este usuario?')">
                                                <input type="hidden" name="action" value="activate">
                                                <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                                <button type="submit" class="btn-activate">
                                                    <i class="fas fa-user-check"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay usuarios registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<!-- Modal para editar usuario -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Editar Usuario</h3>
        <form id="editForm" method="POST" action="" class="admin-form">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_id_usuario" name="id_usuario">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_nombre_completo">Nombre Completo *</label>
                    <input type="text" id="edit_nombre_completo" name="nombre_completo" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_correo_usuario">Correo Electrónico *</label>
                    <input type="email" id="edit_correo_usuario" name="correo_usuario" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_telefono">Teléfono</label>
                    <input type="text" id="edit_telefono" name="telefono">
                </div>
                
                <div class="form-group">
                    <label for="edit_estado_usuario">Estado</label>
                    <select id="edit_estado_usuario" name="estado_usuario" required>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                        <option value="suspendido">Suspendido</option>
                    </select>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="cta-button">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editUser(usuario) {
    document.getElementById('edit_id_usuario').value = usuario.id_usuario;
    document.getElementById('edit_nombre_completo').value = usuario.nombre_completo || '';
    document.getElementById('edit_correo_usuario').value = usuario.correo_usuario;
    document.getElementById('edit_telefono').value = usuario.telefono || '';
    document.getElementById('edit_estado_usuario').value = usuario.estado_usuario;
    
    document.getElementById('editModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

document.querySelector('.close').onclick = closeModal;

window.onclick = function(event) {
    if (event.target == document.getElementById('editModal')) {
        closeModal();
    }
}
</script>

<?php include '../includes/footer.php'; ?>

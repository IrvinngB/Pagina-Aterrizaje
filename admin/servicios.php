<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

requireAdmin();
$pageTitle = 'Gestión de Servicios';

$message = '';
$messageType = 'success';

// Procesar acciones (agregar, editar, eliminar)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add') {
        $nombre = sanitizeInput($_POST['nombre_servicio']);
        $descripcion = sanitizeInput($_POST['descripcion']);
        $precio = floatval($_POST['precio_base']);
        $duracion = intval($_POST['duracion_estimada']);
        $categoria = intval($_POST['id_categoria']);
        
        if (!empty($nombre) && !empty($descripcion)) {
            $stmt = $conn->prepare("INSERT INTO Servicios (nombre_servicio, descripcion, precio_base, duracion_estimada, id_categoria) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $duracion, $categoria);
            
            if ($stmt->execute()) {
                $message = 'Servicio agregado correctamente.';
            } else {
                $message = 'Error al agregar el servicio.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    } elseif ($action == 'edit') {
        $id = intval($_POST['id_servicio']);
        $nombre = sanitizeInput($_POST['nombre_servicio']);
        $descripcion = sanitizeInput($_POST['descripcion']);
        $precio = floatval($_POST['precio_base']);
        $duracion = intval($_POST['duracion_estimada']);
        $categoria = intval($_POST['id_categoria']);
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        if (!empty($nombre) && !empty($descripcion) && $id > 0) {
            $stmt = $conn->prepare("UPDATE Servicios SET nombre_servicio = ?, descripcion = ?, precio_base = ?, duracion_estimada = ?, id_categoria = ?, activo = ? WHERE id_servicio = ?");
            $stmt->bind_param("ssdiiii", $nombre, $descripcion, $precio, $duracion, $categoria, $activo, $id);
            
            if ($stmt->execute()) {
                $message = 'Servicio actualizado correctamente.';
            } else {
                $message = 'Error al actualizar el servicio.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    } elseif ($action == 'delete') {
        $id = intval($_POST['id_servicio']);
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE Servicios SET activo = 0 WHERE id_servicio = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $message = 'Servicio desactivado correctamente.';
            } else {
                $message = 'Error al desactivar el servicio.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    }
}

// Obtener categorías para el select
$categorias_result = $conn->query("SELECT * FROM Categorias WHERE activo = 1 ORDER BY nombre_categoria");

// Obtener servicios
$servicios_result = $conn->query("SELECT s.*, c.nombre_categoria FROM Servicios s LEFT JOIN Categorias c ON s.id_categoria = c.id_categoria ORDER BY s.fecha_creacion DESC");

include '../includes/header.php';
?>

<main class="container-fluid p-0">
    <section class="admin-section">
        <div class="admin-header">
            <h1><i class="fas fa-cogs"></i> Gestión de Servicios</h1>
            <p>Administrar los servicios ofrecidos por la empresa</p>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Formulario para agregar servicio -->
        <div class="admin-card">
            <h3>Agregar Nuevo Servicio</h3>
            <form method="POST" action="" class="admin-form">
                <input type="hidden" name="action" value="add">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre_servicio">Nombre del Servicio *</label>
                        <input type="text" id="nombre_servicio" name="nombre_servicio" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_categoria">Categoría</label>
                        <select id="id_categoria" name="id_categoria">
                            <option value="">Sin categoría</option>
                            <?php while($categoria = $categorias_result->fetch_assoc()): ?>
                                <option value="<?php echo $categoria['id_categoria']; ?>">
                                    <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción *</label>
                    <textarea id="descripcion" name="descripcion" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="precio_base">Precio Base ($)</label>
                        <input type="number" id="precio_base" name="precio_base" step="0.01" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="duracion_estimada">Duración Estimada (minutos)</label>
                        <input type="number" id="duracion_estimada" name="duracion_estimada" min="0">
                    </div>
                </div>
                
                <button type="submit" class="cta-button">
                    <i class="fas fa-plus"></i> Agregar Servicio
                </button>
            </form>
        </div>
        
        <!-- Lista de servicios -->
        <div class="admin-card">
            <h3>Servicios Existentes</h3>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Duración</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($servicios_result && $servicios_result->num_rows > 0): ?>
                            <?php while($servicio = $servicios_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $servicio['id_servicio']; ?></td>
                                    <td><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></td>
                                    <td><?php echo htmlspecialchars($servicio['nombre_categoria'] ?? 'Sin categoría'); ?></td>
                                    <td>$<?php echo number_format($servicio['precio_base'] ?? 0, 2); ?></td>
                                    <td><?php echo $servicio['duracion_estimada'] ?? 'N/A'; ?> min</td>
                                    <td>
                                        <span class="status <?php echo $servicio['activo'] ? 'active' : 'inactive'; ?>">
                                            <?php echo $servicio['activo'] ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <button class="btn-edit" onclick="editService(<?php echo htmlspecialchars(json_encode($servicio)); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de desactivar este servicio?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_servicio" value="<?php echo $servicio['id_servicio']; ?>">
                                            <button type="submit" class="btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay servicios registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<!-- Modal para editar servicio -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Editar Servicio</h3>
        <form id="editForm" method="POST" action="" class="admin-form">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_id_servicio" name="id_servicio">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_nombre_servicio">Nombre del Servicio *</label>
                    <input type="text" id="edit_nombre_servicio" name="nombre_servicio" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_id_categoria">Categoría</label>
                    <select id="edit_id_categoria" name="id_categoria">
                        <option value="">Sin categoría</option>
                        <?php 
                        $categorias_result->data_seek(0);
                        while($categoria = $categorias_result->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $categoria['id_categoria']; ?>">
                                <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="edit_descripcion">Descripción *</label>
                <textarea id="edit_descripcion" name="descripcion" required></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_precio_base">Precio Base ($)</label>
                    <input type="number" id="edit_precio_base" name="precio_base" step="0.01" min="0">
                </div>
                
                <div class="form-group">
                    <label for="edit_duracion_estimada">Duración Estimada (minutos)</label>
                    <input type="number" id="edit_duracion_estimada" name="duracion_estimada" min="0">
                </div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" id="edit_activo" name="activo" value="1">
                    Servicio activo
                </label>
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
function editService(servicio) {
    document.getElementById('edit_id_servicio').value = servicio.id_servicio;
    document.getElementById('edit_nombre_servicio').value = servicio.nombre_servicio;
    document.getElementById('edit_descripcion').value = servicio.descripcion;
    document.getElementById('edit_precio_base').value = servicio.precio_base || '';
    document.getElementById('edit_duracion_estimada').value = servicio.duracion_estimada || '';
    document.getElementById('edit_id_categoria').value = servicio.id_categoria || '';
    document.getElementById('edit_activo').checked = servicio.activo == 1;
    
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

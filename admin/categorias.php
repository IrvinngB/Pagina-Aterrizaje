<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

requireAdmin();
$pageTitle = 'Gestión de Categorías';

$message = '';
$messageType = 'success';

// Procesar acciones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add') {
        $nombre = sanitizeInput($_POST['nombre_categoria']);
        $descripcion = sanitizeInput($_POST['descripcion']);
        $icono = sanitizeInput($_POST['icono']);
        
        if (!empty($nombre)) {
            $stmt = $conn->prepare("INSERT INTO Categorias (nombre_categoria, descripcion, icono) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $descripcion, $icono);
            
            if ($stmt->execute()) {
                $message = 'Categoría agregada correctamente.';
            } else {
                $message = 'Error al agregar la categoría.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    } elseif ($action == 'edit') {
        $id = intval($_POST['id_categoria']);
        $nombre = sanitizeInput($_POST['nombre_categoria']);
        $descripcion = sanitizeInput($_POST['descripcion']);
        $icono = sanitizeInput($_POST['icono']);
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        if (!empty($nombre) && $id > 0) {
            $stmt = $conn->prepare("UPDATE Categorias SET nombre_categoria = ?, descripcion = ?, icono = ?, activo = ? WHERE id_categoria = ?");
            $stmt->bind_param("sssii", $nombre, $descripcion, $icono, $activo, $id);
            
            if ($stmt->execute()) {
                $message = 'Categoría actualizada correctamente.';
            } else {
                $message = 'Error al actualizar la categoría.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    } elseif ($action == 'delete') {
        $id = intval($_POST['id_categoria']);
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE Categorias SET activo = 0 WHERE id_categoria = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $message = 'Categoría desactivada correctamente.';
            } else {
                $message = 'Error al desactivar la categoría.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    }
}

// Obtener categorías
$categorias_result = $conn->query("SELECT * FROM Categorias ORDER BY nombre_categoria");

include '../includes/header.php';
?>

<main class="container-fluid p-0">
    <section class="admin-section">
        <div class="admin-header">
            <h1><i class="fas fa-tags"></i> Gestión de Categorías</h1>
            <p>Administrar las categorías de servicios</p>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Formulario para agregar categoría -->
        <div class="admin-card">
            <h3>Agregar Nueva Categoría</h3>
            <form method="POST" action="" class="admin-form">
                <input type="hidden" name="action" value="add">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre_categoria">Nombre de la Categoría *</label>
                        <input type="text" id="nombre_categoria" name="nombre_categoria" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="icono">Icono CSS (FontAwesome)</label>
                        <input type="text" id="icono" name="icono" placeholder="fa-cog">
                        <small>Ejemplo: fa-palette, fa-laptop-code, fa-bullhorn</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion"></textarea>
                </div>
                
                <button type="submit" class="cta-button">
                    <i class="fas fa-plus"></i> Agregar Categoría
                </button>
            </form>
        </div>
        
        <!-- Lista de categorías -->
        <div class="admin-card">
            <h3>Categorías Existentes</h3>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Icono</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($categorias_result && $categorias_result->num_rows > 0): ?>
                            <?php while($categoria = $categorias_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $categoria['id_categoria']; ?></td>
                                    <td>
                                        <?php if ($categoria['icono']): ?>
                                            <i class="fas <?php echo htmlspecialchars($categoria['icono']); ?>"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></td>
                                    <td><?php echo htmlspecialchars($categoria['descripcion'] ?? ''); ?></td>
                                    <td>
                                        <span class="status <?php echo $categoria['activo'] ? 'active' : 'inactive'; ?>">
                                            <?php echo $categoria['activo'] ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <button class="btn-edit" onclick="editCategory(<?php echo htmlspecialchars(json_encode($categoria)); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de desactivar esta categoría?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
                                            <button type="submit" class="btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay categorías registradas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<!-- Modal para editar categoría -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Editar Categoría</h3>
        <form id="editForm" method="POST" action="" class="admin-form">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_id_categoria" name="id_categoria">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_nombre_categoria">Nombre de la Categoría *</label>
                    <input type="text" id="edit_nombre_categoria" name="nombre_categoria" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_icono">Icono CSS (FontAwesome)</label>
                    <input type="text" id="edit_icono" name="icono" placeholder="fa-cog">
                </div>
            </div>
            
            <div class="form-group">
                <label for="edit_descripcion">Descripción</label>
                <textarea id="edit_descripcion" name="descripcion"></textarea>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" id="edit_activo" name="activo" value="1">
                    Categoría activa
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
function editCategory(categoria) {
    document.getElementById('edit_id_categoria').value = categoria.id_categoria;
    document.getElementById('edit_nombre_categoria').value = categoria.nombre_categoria;
    document.getElementById('edit_descripcion').value = categoria.descripcion || '';
    document.getElementById('edit_icono').value = categoria.icono || '';
    document.getElementById('edit_activo').checked = categoria.activo == 1;
    
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

<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

requireAdmin();
$pageTitle = 'Gestión de Solicitudes';

$message = '';
$messageType = 'success';

// Procesar acciones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'update_status') {
        $id = intval($_POST['id_solicitud']);
        $estado = sanitizeInput($_POST['estado']);
        $notas_admin = sanitizeInput($_POST['notas_admin']);
        $precio_final = isset($_POST['precio_final']) ? floatval($_POST['precio_final']) : null;
        
        if ($id > 0 && !empty($estado)) {
            // Obtener estado anterior
            $stmt_old = $conn->prepare("SELECT estado FROM SolicitudServicio WHERE id_solicitud = ?");
            $stmt_old->bind_param("i", $id);
            $stmt_old->execute();
            $estado_anterior = $stmt_old->get_result()->fetch_assoc()['estado'];
            $stmt_old->close();
            
            // Actualizar solicitud
            if ($precio_final !== null) {
                $stmt = $conn->prepare("UPDATE SolicitudServicio SET estado = ?, notas_admin = ?, precio_final = ? WHERE id_solicitud = ?");
                $stmt->bind_param("ssdi", $estado, $notas_admin, $precio_final, $id);
            } else {
                $stmt = $conn->prepare("UPDATE SolicitudServicio SET estado = ?, notas_admin = ? WHERE id_solicitud = ?");
                $stmt->bind_param("ssi", $estado, $notas_admin, $id);
            }
            
            if ($stmt->execute()) {
                // Registrar cambio en historial
                $stmt_hist = $conn->prepare("INSERT INTO Historial_Estados (id_solicitud, estado_anterior, estado_nuevo, observaciones) VALUES (?, ?, ?, ?)");
                $stmt_hist->bind_param("isss", $id, $estado_anterior, $estado, $notas_admin);
                $stmt_hist->execute();
                $stmt_hist->close();
                
                $message = 'Solicitud actualizada correctamente.';
            } else {
                $message = 'Error al actualizar la solicitud.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    }
}

// Obtener estadísticas
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
    SUM(CASE WHEN estado = 'confirmado' THEN 1 ELSE 0 END) as confirmados,
    SUM(CASE WHEN estado = 'en_proceso' THEN 1 ELSE 0 END) as en_proceso,
    SUM(CASE WHEN estado = 'completado' THEN 1 ELSE 0 END) as completados,
    SUM(CASE WHEN estado = 'cancelado' THEN 1 ELSE 0 END) as cancelados
    FROM SolicitudServicio";
$stats = $conn->query($stats_query)->fetch_assoc();

// Obtener solicitudes con información del usuario y servicio
$solicitudes_query = "SELECT ss.*, u.nombre_completo, u.correo_usuario, s.nombre_servicio, s.precio_base
    FROM SolicitudServicio ss
    LEFT JOIN Usuarios u ON ss.id_usuario = u.id_usuario
    LEFT JOIN Servicios s ON ss.id_servicio = s.id_servicio
    ORDER BY ss.fecha_solicitud DESC";
$solicitudes_result = $conn->query($solicitudes_query);

include '../includes/header.php';
?>

<main class="container-fluid p-0">
    <section class="admin-section">
        <div class="admin-header">
            <h1><i class="fas fa-clipboard-check"></i> Gestión de Solicitudes</h1>
            <p>Administrar las solicitudes de servicios</p>
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
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['total']; ?></h3>
                    <p>Total Solicitudes</p>
                </div>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['pendientes']; ?></h3>
                    <p>Pendientes</p>
                </div>
            </div>
            
            <div class="stat-card confirmed">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['confirmados']; ?></h3>
                    <p>Confirmados</p>
                </div>
            </div>
            
            <div class="stat-card process">
                <div class="stat-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['en_proceso']; ?></h3>
                    <p>En Proceso</p>
                </div>
            </div>
            
            <div class="stat-card completed">
                <div class="stat-icon">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['completados']; ?></h3>
                    <p>Completados</p>
                </div>
            </div>
            
            <div class="stat-card cancelled">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['cancelados']; ?></h3>
                    <p>Cancelados</p>
                </div>
            </div>
        </div>
        
        <!-- Lista de solicitudes -->
        <div class="admin-card">
            <h3>Lista de Solicitudes</h3>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Fecha Solicitud</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($solicitudes_result && $solicitudes_result->num_rows > 0): ?>
                            <?php while($solicitud = $solicitudes_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $solicitud['id_solicitud']; ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($solicitud['nombre_completo'] ?? 'Usuario eliminado'); ?>
                                        <br><small><?php echo htmlspecialchars($solicitud['correo_usuario'] ?? ''); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($solicitud['nombre_servicio'] ?? 'Servicio eliminado'); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])); ?></td>
                                    <td>
                                        <span class="status <?php echo $solicitud['estado']; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $solicitud['estado'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="priority priority-<?php echo $solicitud['prioridad']; ?>">
                                            <?php echo ucfirst($solicitud['prioridad']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($solicitud['precio_final']): ?>
                                            $<?php echo number_format($solicitud['precio_final'], 2); ?>
                                        <?php elseif ($solicitud['precio_base']): ?>
                                            $<?php echo number_format($solicitud['precio_base'], 2); ?> (base)
                                        <?php else: ?>
                                            No definido
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <button class="btn-edit" onclick="viewSolicitud(<?php echo htmlspecialchars(json_encode($solicitud)); ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-edit" onclick="editSolicitud(<?php echo htmlspecialchars(json_encode($solicitud)); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No hay solicitudes registradas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<!-- Modal para ver solicitud -->
<div id="viewModal" class="modal" style="display: none;">
    <div class="modal-content modal-large">
        <span class="close">&times;</span>
        <h3>Detalles de la Solicitud</h3>
        <div id="solicitudDetails" class="solicitud-details">
            <!-- Contenido dinámico -->
        </div>
    </div>
</div>

<!-- Modal para editar solicitud -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-edit">&times;</span>
        <h3>Editar Solicitud</h3>
        <form id="editForm" method="POST" action="" class="admin-form">
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" id="edit_id_solicitud" name="id_solicitud">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_estado">Estado *</label>
                    <select id="edit_estado" name="estado" required>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmado">Confirmado</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="completado">Completado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_precio_final">Precio Final ($)</label>
                    <input type="number" id="edit_precio_final" name="precio_final" step="0.01" min="0">
                </div>
            </div>
            
            <div class="form-group">
                <label for="edit_notas_admin">Notas del Administrador</label>
                <textarea id="edit_notas_admin" name="notas_admin" rows="4"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeEditModal()">Cancelar</button>
                <button type="submit" class="cta-button">
                    <i class="fas fa-save"></i> Actualizar Solicitud
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function viewSolicitud(solicitud) {
    const details = `
        <div class="solicitud-info">
            <div class="info-section">
                <h4>Información del Cliente</h4>
                <p><strong>Nombre:</strong> ${solicitud.nombre_completo || 'N/A'}</p>
                <p><strong>Correo:</strong> ${solicitud.correo_usuario || 'N/A'}</p>
                <p><strong>Teléfono:</strong> ${solicitud.telefono_contacto || 'N/A'}</p>
                <p><strong>Instrucciones:</strong> ${solicitud.direccion_servicio || 'N/A'}</p>
            </div>
            
            <div class="info-section">
                <h4>Información del Servicio</h4>
                <p><strong>Servicio:</strong> ${solicitud.nombre_servicio || 'N/A'}</p>
                <p><strong>Fecha Solicitud:</strong> ${new Date(solicitud.fecha_solicitud).toLocaleString()}</p>
                <p><strong>Fecha Programada:</strong> ${solicitud.fecha_programada ? new Date(solicitud.fecha_programada).toLocaleString() : 'No definida'}</p>
                <p><strong>Estado:</strong> ${solicitud.estado}</p>
                <p><strong>Prioridad:</strong> ${solicitud.prioridad}</p>
            </div>
            
            <div class="info-section">
                <h4>Precios</h4>
                <p><strong>Precio Base:</strong> $${solicitud.precio_base || '0.00'}</p>
                <p><strong>Precio Final:</strong> $${solicitud.precio_final || 'No definido'}</p>
            </div>
            
            <div class="info-section">
                <h4>Notas</h4>
                <p><strong>Notas del Cliente:</strong> ${solicitud.notas_cliente || 'Sin notas'}</p>
                <p><strong>Notas del Admin:</strong> ${solicitud.notas_admin || 'Sin notas'}</p>
            </div>
        </div>
    `;
    
    document.getElementById('solicitudDetails').innerHTML = details;
    document.getElementById('viewModal').style.display = 'block';
}

function editSolicitud(solicitud) {
    document.getElementById('edit_id_solicitud').value = solicitud.id_solicitud;
    document.getElementById('edit_estado').value = solicitud.estado;
    document.getElementById('edit_precio_final').value = solicitud.precio_final || '';
    document.getElementById('edit_notas_admin').value = solicitud.notas_admin || '';
    
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

document.querySelector('.close').onclick = function() {
    document.getElementById('viewModal').style.display = 'none';
}

document.querySelector('.close-edit').onclick = closeEditModal;

window.onclick = function(event) {
    if (event.target == document.getElementById('viewModal')) {
        document.getElementById('viewModal').style.display = 'none';
    }
    if (event.target == document.getElementById('editModal')) {
        closeEditModal();
    }
}
</script>

<?php include '../includes/footer.php'; ?>

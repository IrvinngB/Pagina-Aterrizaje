<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

requireAdmin();
$pageTitle = 'Mensajes de Contacto';

$message = '';
$messageType = 'success';

// Procesar acciones (marcar como leído, responder, archivar, eliminar)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'update_status') {
        $id = intval($_POST['id_contacto']);
        $estado = sanitizeInput($_POST['estado_mensaje']);
        
        if ($id > 0 && in_array($estado, ['nuevo', 'leido', 'respondido'])) {
            $stmt = $conn->prepare("UPDATE Contacto SET estado_mensaje = ? WHERE id_contacto = ?");
            $stmt->bind_param("si", $estado, $id);
            
            if ($stmt->execute()) {
                $message = 'Estado del mensaje actualizado correctamente.';
            } else {
                $message = 'Error al actualizar el estado del mensaje.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    } elseif ($action == 'delete') {
        $id = intval($_POST['id_contacto']);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM Contacto WHERE id_contacto = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $message = 'Mensaje eliminado correctamente.';
            } else {
                $message = 'Error al eliminar el mensaje.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    } elseif ($action == 'reply') {
        $id = intval($_POST['id_contacto']);
        $respuesta = sanitizeInput($_POST['respuesta']);
        
        if ($id > 0 && !empty($respuesta)) {
            // Obtener datos del mensaje original
            $stmt = $conn->prepare("SELECT nombre_contacto, correo_contacto, asunto FROM Contacto WHERE id_contacto = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $contacto = $result->fetch_assoc();
                
                // Aquí iría el código para enviar el email
                // Por ahora solo actualizamos el estado
                $stmt2 = $conn->prepare("UPDATE Contacto SET estado_mensaje = 'respondido' WHERE id_contacto = ?");
                $stmt2->bind_param("i", $id);
                
                if ($stmt2->execute()) {
                    $message = 'Respuesta enviada correctamente a ' . htmlspecialchars($contacto['correo_contacto']);
                } else {
                    $message = 'Error al enviar la respuesta.';
                    $messageType = 'danger';
                }
                $stmt2->close();
            }
            $stmt->close();
        }
    }
}

// Obtener filtros
$filtro_estado = $_GET['estado'] ?? '';
$busqueda = $_GET['busqueda'] ?? '';

// Construir consulta con filtros
$where_conditions = [];
$params = [];
$types = '';

if (!empty($filtro_estado)) {
    $where_conditions[] = "estado_mensaje = ?";
    $params[] = $filtro_estado;
    $types .= 's';
}

if (!empty($busqueda)) {
    $where_conditions[] = "(nombre_contacto LIKE ? OR correo_contacto LIKE ? OR asunto LIKE ? OR mensaje LIKE ?)";
    $search_term = "%$busqueda%";
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
    $types .= 'ssss';
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Obtener mensajes de contacto
$query = "SELECT c.*, u.nombre_completo as usuario_nombre 
          FROM Contacto c 
          LEFT JOIN Usuarios u ON c.id_usuario = u.id_usuario 
          $where_clause 
          ORDER BY c.fecha_mensaje DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$mensajes_result = $stmt->get_result();

// Obtener estadísticas
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN estado_mensaje = 'nuevo' THEN 1 ELSE 0 END) as nuevos,
    SUM(CASE WHEN estado_mensaje = 'leido' THEN 1 ELSE 0 END) as leidos,
    SUM(CASE WHEN estado_mensaje = 'respondido' THEN 1 ELSE 0 END) as respondidos
    FROM Contacto";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

include '../includes/header.php';
?>

<main class="container-fluid p-0">
    <section class="admin-header">
        <div class="admin-header-content">
            <h1><i class="fas fa-envelope-open-text"></i> Mensajes de Contacto</h1>
            <p>Ver y responder mensajes de los visitantes de la página</p>
            
            <div class="admin-actions">
                <a href="dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a>
                <div class="action-buttons">
                    <button class="btn-filter" onclick="toggleFilters()"><i class="fas fa-filter"></i> Filtrar</button>
                    <button class="btn-export" onclick="exportarMensajes()"><i class="fas fa-download"></i> Exportar</button>
                </div>
            </div>
        </div>
    </section>

    <?php if (!empty($message)): ?>
        <div class="container">
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        </div>
    <?php endif; ?>

    <section class="content-section">
        <div class="container">
            <!-- Estadísticas -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-inbox"></i></div>
                    <div class="stat-data">
                        <div class="stat-number"><?php echo $stats['total']; ?></div>
                        <div class="stat-label">Total Mensajes</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon new"><i class="fas fa-envelope"></i></div>
                    <div class="stat-data">
                        <div class="stat-number"><?php echo $stats['nuevos']; ?></div>
                        <div class="stat-label">Nuevos</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon pending"><i class="fas fa-eye"></i></div>
                    <div class="stat-data">
                        <div class="stat-number"><?php echo $stats['leidos']; ?></div>
                        <div class="stat-label">Leídos</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon success"><i class="fas fa-reply"></i></div>
                    <div class="stat-data">
                        <div class="stat-number"><?php echo $stats['respondidos']; ?></div>
                        <div class="stat-label">Respondidos</div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="message-filters" id="message-filters">
                <form method="GET" action="" class="filter-form">
                    <div class="filter-group">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="busqueda" placeholder="Buscar en mensajes..." 
                                   value="<?php echo htmlspecialchars($busqueda); ?>">
                        </div>
                        
                        <div class="filter-select-group">
                            <select name="estado" class="filter-select">
                                <option value="">Todos los estados</option>
                                <option value="nuevo" <?php echo $filtro_estado === 'nuevo' ? 'selected' : ''; ?>>Nuevos</option>
                                <option value="leido" <?php echo $filtro_estado === 'leido' ? 'selected' : ''; ?>>Leídos</option>
                                <option value="respondido" <?php echo $filtro_estado === 'respondido' ? 'selected' : ''; ?>>Respondidos</option>
                            </select>
                            
                            <button type="submit" class="btn-apply-filter">
                                <i class="fas fa-search"></i> Aplicar
                            </button>
                            
                            <a href="mensajes.php" class="btn-clear-filter">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Lista de mensajes -->
            <div class="admin-card">
                <div class="card-header">
                    <h3>Mensajes de Contacto</h3>
                    <div class="card-actions">
                        <button class="btn-refresh" onclick="location.reload()">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Asunto</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($mensajes_result && $mensajes_result->num_rows > 0): ?>
                                <?php while($mensaje = $mensajes_result->fetch_assoc()): ?>
                                    <tr class="message-row <?php echo $mensaje['estado_mensaje']; ?>">
                                        <td><?php echo $mensaje['id_contacto']; ?></td>
                                        <td>
                                            <div class="contact-info">
                                                <strong><?php echo htmlspecialchars($mensaje['nombre_contacto']); ?></strong>
                                                <?php if ($mensaje['telefono_contacto']): ?>
                                                    <small><?php echo htmlspecialchars($mensaje['telefono_contacto']); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($mensaje['correo_contacto']); ?></td>
                                        <td>
                                            <div class="subject-preview">
                                                <strong><?php echo htmlspecialchars($mensaje['asunto'] ?: 'Sin asunto'); ?></strong>
                                                <small><?php echo substr(htmlspecialchars($mensaje['mensaje']), 0, 50) . '...'; ?></small>
                                            </div>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($mensaje['fecha_mensaje'])); ?></td>
                                        <td>
                                            <span class="status <?php echo $mensaje['estado_mensaje']; ?>">
                                                <?php
                                                switch($mensaje['estado_mensaje']) {
                                                    case 'nuevo': echo '<i class="fas fa-envelope"></i> Nuevo'; break;
                                                    case 'leido': echo '<i class="fas fa-eye"></i> Leído'; break;
                                                    case 'respondido': echo '<i class="fas fa-reply"></i> Respondido'; break;
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td class="actions">
                                            <button class="btn-view" onclick="viewMessage(<?php echo htmlspecialchars(json_encode($mensaje)); ?>)" title="Ver mensaje">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn-reply" onclick="replyMessage(<?php echo $mensaje['id_contacto']; ?>, '<?php echo htmlspecialchars($mensaje['correo_contacto']); ?>', '<?php echo htmlspecialchars($mensaje['asunto']); ?>')" title="Responder">
                                                <i class="fas fa-reply"></i>
                                            </button>
                                            <div class="dropdown">
                                                <button class="btn-more" title="Más opciones">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-content">
                                                    <button onclick="changeStatus(<?php echo $mensaje['id_contacto']; ?>, 'leido')">
                                                        <i class="fas fa-eye"></i> Marcar como leído
                                                    </button>
                                                    <button onclick="changeStatus(<?php echo $mensaje['id_contacto']; ?>, 'respondido')">
                                                        <i class="fas fa-check"></i> Marcar como respondido
                                                    </button>
                                                    <button onclick="deleteMessage(<?php echo $mensaje['id_contacto']; ?>)" class="danger">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No hay mensajes que coincidan con los filtros</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Modal para ver mensaje -->
<div id="viewModal" class="modal" style="display: none;">
    <div class="modal-content large">
        <div class="modal-header">
            <h3>Detalles del Mensaje</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="message-details">
                <div class="detail-row">
                    <label>De:</label>
                    <span id="view-nombre"></span>
                </div>
                <div class="detail-row">
                    <label>Email:</label>
                    <span id="view-email"></span>
                </div>
                <div class="detail-row">
                    <label>Teléfono:</label>
                    <span id="view-telefono"></span>
                </div>
                <div class="detail-row">
                    <label>Asunto:</label>
                    <span id="view-asunto"></span>
                </div>
                <div class="detail-row">
                    <label>Fecha:</label>
                    <span id="view-fecha"></span>
                </div>
                <div class="detail-row full">
                    <label>Mensaje:</label>
                    <div id="view-mensaje" class="message-content"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeModal('viewModal')">Cerrar</button>
            <button type="button" class="btn-primary" onclick="replyFromView()">Responder</button>
        </div>
    </div>
</div>

<!-- Modal para responder -->
<div id="replyModal" class="modal" style="display: none;">
    <div class="modal-content large">
        <div class="modal-header">
            <h3>Responder Mensaje</h3>
            <span class="close">&times;</span>
        </div>
        <form id="replyForm" method="POST" action="">
            <div class="modal-body">
                <input type="hidden" name="action" value="reply">
                <input type="hidden" id="reply-id" name="id_contacto">
                
                <div class="form-group">
                    <label>Para:</label>
                    <input type="email" id="reply-email" readonly class="readonly">
                </div>
                
                <div class="form-group">
                    <label>Asunto:</label>
                    <input type="text" id="reply-subject" readonly class="readonly">
                </div>
                
                <div class="form-group">
                    <label for="respuesta">Respuesta *</label>
                    <textarea id="respuesta" name="respuesta" required rows="8" 
                              placeholder="Escribe tu respuesta aquí..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('replyModal')">Cancelar</button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane"></i> Enviar Respuesta
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Formularios ocultos para acciones -->
<form id="statusForm" method="POST" style="display: none;">
    <input type="hidden" name="action" value="update_status">
    <input type="hidden" id="status-id" name="id_contacto">
    <input type="hidden" id="status-estado" name="estado_mensaje">
</form>

<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" id="delete-id" name="id_contacto">
</form>

<style>
:root {
    --primary-color: #114093;
    --secondary-color: #f2f6f9;
    --accent-color: #e94e1a;
    --text-color: #333;
    --background-color: #ffffff;
    --primary-light: #2a5bb7;
    --primary-dark: #0c2f6e;
    --accent-light: #ff6a3c;
    --accent-dark: #c93f12;
    --secondary-dark: #d1dce8;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
}

/* Admin Header */
.admin-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
}

.admin-header-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.admin-header h1 {
    font-size: 2.2rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.admin-header h1 i {
    color: var(--accent-color);
}

.admin-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    text-decoration: none;
    background: rgba(255, 255, 255, 0.15);
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    font-weight: 600;
    transition: background 0.3s ease;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.25);
    color: white;
}

.action-buttons {
    display: flex;
    gap: 1rem;
}

.btn-filter, .btn-export {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-filter {
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

.btn-export {
    background: var(--accent-color);
    color: white;
}

/* Content Section */
.content-section {
    padding: 1rem 0 4rem;
    background: var(--secondary-color);
    min-height: calc(100vh - 300px);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    background: var(--primary-color);
}

.stat-icon.new { background: var(--accent-color); }
.stat-icon.pending { background: var(--warning-color); }
.stat-icon.success { background: var(--success-color); }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
}

.stat-label {
    color: var(--text-color);
    opacity: 0.8;
}

/* Filters */
.message-filters {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    display: none;
}

.message-filters.active {
    display: block;
}

.filter-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.filter-group {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 300px;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.search-box input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 2.8rem;
    border: 1px solid var(--secondary-dark);
    border-radius: 8px;
    font-size: 1rem;
}

.filter-select-group {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.filter-select {
    padding: 0.8rem;
    border: 1px solid var(--secondary-dark);
    border-radius: 8px;
    font-size: 1rem;
}

.btn-apply-filter, .btn-clear-filter {
    padding: 0.8rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-apply-filter {
    background: var(--primary-color);
    color: white;
    border: none;
    cursor: pointer;
}

.btn-clear-filter {
    background: var(--secondary-dark);
    color: var(--text-color);
}

/* Admin Card */
.admin-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    overflow: hidden;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--secondary-dark);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--primary-color);
}

.card-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-refresh {
    padding: 0.5rem 1rem;
    background: var(--secondary-color);
    color: var(--primary-color);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-refresh:hover {
    background: var(--secondary-dark);
}

/* Table */
.table-responsive {
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th {
    background: var(--secondary-color);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--primary-color);
    border-bottom: 1px solid var(--secondary-dark);
}

.admin-table td {
    padding: 1rem;
    border-bottom: 1px solid #eee;
    vertical-align: top;
}

.message-row.nuevo {
    background: rgba(33, 150, 243, 0.05);
    border-left: 3px solid #2196F3;
}

.contact-info strong {
    display: block;
    color: var(--primary-color);
}

.contact-info small {
    color: #666;
    font-size: 0.85rem;
}

.subject-preview strong {
    display: block;
    color: var(--text-color);
    margin-bottom: 0.25rem;
}

.subject-preview small {
    color: #666;
    font-size: 0.85rem;
}

.status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
    color: white;
}

.status.nuevo { background: #2196F3; }
.status.leido { background: var(--warning-color); color: #333; }
.status.respondido { background: var(--success-color); }

/* Actions */
.actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.btn-view, .btn-reply, .btn-more {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-view {
    background: var(--primary-color);
    color: white;
}

.btn-reply {
    background: var(--success-color);
    color: white;
}

.btn-more {
    background: var(--secondary-dark);
    color: var(--text-color);
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background: white;
    min-width: 180px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    border-radius: 8px;
    z-index: 1000;
    overflow: hidden;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown-content button {
    width: 100%;
    padding: 0.75rem 1rem;
    border: none;
    background: none;
    text-align: left;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: background 0.3s ease;
}

.dropdown-content button:hover {
    background: var(--secondary-color);
}

.dropdown-content button.danger {
    color: var(--danger-color);
}

/* Modal */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-content.large {
    max-width: 800px;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--secondary-dark);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    color: var(--primary-color);
    font-weight: 600;
}

.close {
    font-size: 1.5rem;
    cursor: pointer;
    color: #999;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1.5rem;
    border-top: 1px solid var(--secondary-dark);
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

/* Message Details */
.message-details {
    display: grid;
    gap: 1rem;
}

.detail-row {
    display: grid;
    grid-template-columns: 120px 1fr;
    gap: 1rem;
    align-items: start;
}

.detail-row.full {
    grid-template-columns: 1fr;
}

.detail-row label {
    font-weight: 600;
    color: var(--primary-color);
}

.message-content {
    background: var(--secondary-color);
    padding: 1rem;
    border-radius: 8px;
    line-height: 1.6;
    white-space: pre-wrap;
}

/* Form Elements */
.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--primary-color);
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid var(--secondary-dark);
    border-radius: 8px;
    font-size: 1rem;
}

.form-group input.readonly {
    background: var(--secondary-color);
    color: #666;
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

/* Buttons */
.btn-primary, .btn-secondary {
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-secondary {
    background: var(--secondary-dark);
    color: var(--text-color);
}

.btn-secondary:hover {
    background: #bbb;
}

/* Alert */
.alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-actions {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-group {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box {
        min-width: auto;
    }
    
    .admin-table {
        font-size: 0.9rem;
    }
    
    .admin-table th,
    .admin-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .actions {
        flex-direction: column;
    }
}
</style>

<script>
let currentMessageId = null;

function toggleFilters() {
    const filters = document.getElementById('message-filters');
    filters.classList.toggle('active');
}

function viewMessage(mensaje) {
    document.getElementById('view-nombre').textContent = mensaje.nombre_contacto;
    document.getElementById('view-email').textContent = mensaje.correo_contacto;
    document.getElementById('view-telefono').textContent = mensaje.telefono_contacto || 'No proporcionado';
    document.getElementById('view-asunto').textContent = mensaje.asunto || 'Sin asunto';
    document.getElementById('view-fecha').textContent = new Date(mensaje.fecha_mensaje).toLocaleString();
    document.getElementById('view-mensaje').textContent = mensaje.mensaje;
    
    currentMessageId = mensaje.id_contacto;
    document.getElementById('viewModal').style.display = 'flex';
    
    // Marcar como leído si es nuevo
    if (mensaje.estado_mensaje === 'nuevo') {
        changeStatus(mensaje.id_contacto, 'leido');
    }
}

function replyMessage(id, email, asunto) {
    document.getElementById('reply-id').value = id;
    document.getElementById('reply-email').value = email;
    document.getElementById('reply-subject').value = 'Re: ' + (asunto || 'Sin asunto');
    document.getElementById('respuesta').value = '';
    
    document.getElementById('replyModal').style.display = 'flex';
}

function replyFromView() {
    if (currentMessageId) {
        const email = document.getElementById('view-email').textContent;
        const asunto = document.getElementById('view-asunto').textContent;
        closeModal('viewModal');
        replyMessage(currentMessageId, email, asunto);
    }
}

function changeStatus(id, estado) {
    document.getElementById('status-id').value = id;
    document.getElementById('status-estado').value = estado;
    document.getElementById('statusForm').submit();
}

function deleteMessage(id) {
    if (confirm('¿Está seguro de que desea eliminar este mensaje? Esta acción no se puede deshacer.')) {
        document.getElementById('delete-id').value = id;
        document.getElementById('deleteForm').submit();
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function exportarMensajes() {
    alert('Funcionalidad de exportación en desarrollo');
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Cerrar modales al hacer clic en la X
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });
    
    // Cerrar modales al hacer clic fuera
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>

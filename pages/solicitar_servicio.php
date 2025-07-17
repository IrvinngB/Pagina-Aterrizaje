<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

// Verificar que el usuario esté logueado
requireLogin();

$pageTitle = 'Solicitar Servicio';
$message = '';
$messageType = 'success';

// Obtener el ID del servicio
$id_servicio = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener información del servicio
$servicio = null;
if ($id_servicio > 0) {
    $stmt = $conn->prepare("SELECT s.*, c.nombre_categoria FROM Servicios s LEFT JOIN Categorias c ON s.id_categoria = c.id_categoria WHERE s.id_servicio = ? AND s.activo = 1");
    $stmt->bind_param("i", $id_servicio);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $servicio = $result->fetch_assoc();
    } else {
        header('Location: servicios.php');
        exit;
    }
    $stmt->close();
} else {
    header('Location: servicios.php');
    exit;
}

// Procesar formulario de solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_programada = sanitizeInput($_POST['fecha_programada']);
    $notas_cliente = sanitizeInput($_POST['notas_cliente'] ?? '');
    $direccion_servicio = sanitizeInput($_POST['direccion_servicio']);
    $telefono_contacto = sanitizeInput($_POST['telefono_contacto']);
    $prioridad = sanitizeInput($_POST['prioridad'] ?? 'media');
    
    if (empty($fecha_programada) || empty($direccion_servicio) || empty($telefono_contacto)) {
        $message = 'Por favor, complete todos los campos obligatorios.';
        $messageType = 'danger';
    } else {
        // Insertar solicitud en la base de datos
        $stmt = $conn->prepare("INSERT INTO SolicitudServicio (id_usuario, id_servicio, fecha_programada, notas_cliente, direccion_servicio, telefono_contacto, prioridad, precio_final) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $precio_final = $servicio['precio_base'];
        $stmt->bind_param("iisssssd", $_SESSION['user_id'], $id_servicio, $fecha_programada, $notas_cliente, $direccion_servicio, $telefono_contacto, $prioridad, $precio_final);
        
        if ($stmt->execute()) {
            // Obtener el ID de la solicitud recién creada
            $solicitud_id = $conn->insert_id;
            
            // Redireccionar a la página de factura con el ID de la solicitud
            header("Location: factura_detalle.php?id=" . $solicitud_id);
            exit;
        } else {
            $message = 'Error al enviar la solicitud. Por favor, intente nuevamente.';
            $messageType = 'danger';
        }
        
        $stmt->close();
    }
}

include '../includes/header.php';
?>

<main class="container-fluid p-0">
    <br>
    <section id="solicitar-servicio">
        <h1>Solicitar Servicio</h1>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="service-request-container">
            <div class="service-details">
                <h2>Detalles del Servicio</h2>
                <div class="service-info-card">
                    <h3><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></h3>
                    <p class="service-category"><?php echo htmlspecialchars($servicio['nombre_categoria'] ?? 'General'); ?></p>
                    <p class="service-description"><?php echo htmlspecialchars($servicio['descripcion']); ?></p>
                    <?php if ($servicio['precio_base']): ?>
                        <div class="service-price">
                            Precio base: $<?php echo number_format($servicio['precio_base'], 2); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($servicio['duracion_estimada']): ?>
                        <div class="service-duration">
                            <i class="fas fa-clock"></i> Duración estimada: <?php echo $servicio['duracion_estimada']; ?> minutos
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <form id="serviceRequestForm" method="POST" action="">
                <h2>Información de la Solicitud</h2>
                
                <label for="fecha_programada">Fecha y Hora Preferida *</label>
                <input type="datetime-local" id="fecha_programada" name="fecha_programada" 
                       min="<?php echo date('Y-m-d\TH:i'); ?>" required>
                
                <label for="direccion_servicio">Instrucciones especificas *</label>
                <textarea id="direccion_servicio" name="direccion_servicio" 
                          placeholder="Ingrese la dirección completa donde se realizará el servicio..." required></textarea>
                
                <label for="telefono_contacto">Teléfono de Contacto *</label>
                <input type="tel" id="telefono_contacto" name="telefono_contacto" 
                       placeholder="(123) 456-7890" required>
                
                <label for="prioridad">Prioridad</label>
                <select id="prioridad" name="prioridad">
                    <option value="baja">Baja</option>
                    <option value="media" selected>Media</option>
                    <option value="alta">Alta</option>
                    <option value="urgente">Urgente</option>
                </select>
                
                <label for="notas_cliente">Notas Adicionales</label>
                <textarea id="notas_cliente" name="notas_cliente" 
                          placeholder="Proporcione cualquier información adicional sobre el servicio..."></textarea>
                
                <div class="form-actions">
                    <a href="servicios.php" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="cta-button">Solicitar Servicio <i class="fas fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>

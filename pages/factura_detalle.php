<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

$pageTitle = 'Factura';

// Verificar si hay un ID de solicitud proporcionado
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_solicitud = intval($_GET['id']);
    
    // Consultar los datos de la solicitud junto con la información del servicio y usuario
    $query = "SELECT s.*, 
                    srv.nombre_servicio, srv.descripcion, srv.duracion_estimada,
                    u.nombre_completo, u.correo_usuario, u.telefono,
                    c.nombre_categoria
              FROM SolicitudServicio s
              JOIN Servicios srv ON s.id_servicio = srv.id_servicio
              JOIN Usuarios u ON s.id_usuario = u.id_usuario
              LEFT JOIN Categorias c ON srv.id_categoria = c.id_categoria
              WHERE s.id_solicitud = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_solicitud);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $solicitud = $result->fetch_assoc();
        
        // Datos para la factura
        $nombre = htmlspecialchars($solicitud['nombre_completo']);
        $email = htmlspecialchars($solicitud['correo_usuario']);
        $telefono = htmlspecialchars($solicitud['telefono_contacto'] ?: $solicitud['telefono']);
        $servicio = htmlspecialchars($solicitud['nombre_servicio']);
        $categoria = htmlspecialchars($solicitud['nombre_categoria'] ?: 'General');
        $mensaje = htmlspecialchars($solicitud['notas_cliente']);
        $fecha = date('d/m/Y', strtotime($solicitud['fecha_solicitud']));
        $hora = date('H:i:s', strtotime($solicitud['fecha_solicitud']));
        $numeroFactura = 'FAC-' . $id_solicitud . '-' . date('Ymd');
        $precio = number_format($solicitud['precio_final'], 2);
        $moneda = 'USD';
        $direccion = htmlspecialchars($solicitud['direccion_servicio']);
        $fecha_programada = date('d/m/Y H:i', strtotime($solicitud['fecha_programada']));
        
        // Verificar si la factura ya existe
        $stmtCheck = $conn->prepare("SELECT id_factura FROM facturas WHERE numero_factura = ?");
        $stmtCheck->bind_param("s", $numeroFactura);
        $stmtCheck->execute();
        $resCheck = $stmtCheck->get_result();
        if ($resCheck->num_rows == 0) {
            // Insertar la factura con los campos existentes
            $stmtInsert = $conn->prepare("INSERT INTO facturas (numero_factura, id_usuario, id_servicio, subtotal, moneda, notas, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $subtotal = floatval($solicitud['precio_final']);
            $estado = 'emitida';
            $stmtInsert->bind_param("siidsss", $numeroFactura, $solicitud['id_usuario'], $solicitud['id_servicio'], $subtotal, $moneda, $mensaje, $estado);
            $stmtInsert->execute();
            $stmtInsert->close();
        }
        $stmtCheck->close();
        // Incluir el encabezado
        include '../includes/header.php';
?>

<style>
    .factura-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    }
    
    .factura-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .factura-logo {
        display: flex;
        align-items: center;
    }
    
    .factura-logo img {
        height: 50px;
        margin-right: 10px;
    }
    
    .factura-info {
        text-align: right;
    }
    
    .factura-numero {
        font-size: 1.2rem;
        color: var(--accent-color);
        font-weight: bold;
    }
    
    .factura-fecha {
        font-size: 0.9rem;
        color: #666;
    }
    
    .factura-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .factura-section h2 {
        color: var(--primary-color);
        margin-bottom: 1rem;
        font-size: 1.3rem;
    }
    
    .factura-table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
    }
    
    .factura-table th {
        background-color: var(--primary-color);
        color: white;
        text-align: left;
        padding: 0.8rem;
    }
    
    .factura-table td {
        border: 1px solid #ddd;
        padding: 0.8rem;
    }
    
    .factura-total {
        background-color: #f9f9f9;
        text-align: right;
        font-weight: bold;
        padding: 0.5rem;
        font-size: 1.1rem;
    }
    
    .factura-footer {
        text-align: center;
        margin-top: 2rem;
        color: #666;
    }
    
    .factura-actions {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
        gap: 1rem;
    }
    
    .btn-imprimir {
        background-color: var(--accent-color);
        color: white;
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-volver {
        background-color: var(--primary-color);
        color: white;
        text-decoration: none;
        padding: 0.7rem 1.5rem;
        border-radius: 4px;
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    .btn-imprimir:hover, .btn-volver:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
    
    @media print {
        .factura-actions, header, footer {
            display: none !important;
        }
        
        .factura-container {
            box-shadow: none;
            margin: 0;
            padding: 0;
        }
        
        body {
            background-color: white;
        }
    }
</style>

<main>
    <div class="factura-container">
        <div class="factura-header">
            <div class="factura-logo">
                <img src="<?php echo $root_path; ?>assets/images/LogoColorFinal.svg" alt="Logo de PixelPerfect" />
                <div>
                    <div class="logo-text">
                        <span class="pixel">Pixel</span><span class="perfect">Perfect</span>
                    </div>
                    <small>Soluciones innovadoras para tu negocio</small>
                </div>
            </div>
            
            <div class="factura-info">
                <div class="factura-numero">Factura #<?php echo $numeroFactura; ?></div>
                <div class="factura-fecha">Fecha: <?php echo $fecha; ?> - Hora: <?php echo $hora; ?></div>
            </div>
        </div>
        
        <div class="factura-section">
            <h2><i class="fas fa-user"></i> Datos del Cliente</h2>
            <table style="width:100%">
                <tr>
                    <td><strong>Nombre:</strong></td>
                    <td><?php echo $nombre; ?></td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo $email; ?></td>
                </tr>
                <tr>
                    <td><strong>Teléfono:</strong></td>
                    <td><?php echo $telefono; ?></td>
                </tr>
                <tr>
                    <td><strong>Dirección/Notas:</strong></td>
                    <td><?php echo $direccion; ?></td>
                </tr>
                <tr>
                    <td><strong>Fecha Programada:</strong></td>
                    <td><?php echo $fecha_programada; ?></td>
                </tr>
            </table>
        </div>
        
        <div class="factura-section">
            <h2><i class="fas fa-clipboard-list"></i> Detalle del Servicio</h2>
            <table class="factura-table">
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $servicio; ?></td>
                        <td><?php echo $categoria; ?></td>
                        <td>$<?php echo $precio; ?> <?php echo $moneda; ?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="factura-total">Total:</td>
                        <td>$<?php echo $precio; ?> <?php echo $moneda; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <?php if (!empty($mensaje)): ?>
        <div class="factura-section">
            <h2><i class="fas fa-comment"></i> Mensaje del Cliente</h2>
            <p><?php echo nl2br($mensaje); ?></p>
        </div>
        <?php endif; ?>
        
        <div class="factura-footer">
            <p>¡Gracias por confiar en PixelPerfect!</p>
            <p>Para más información, contáctanos en info@pixelperfect.com</p>
            <div class="factura-actions">
                <button onclick="window.print()" class="btn-imprimir"><i class="fas fa-print"></i> Imprimir</button>
                <a href="servicios.php" class="btn-volver"><i class="fas fa-arrow-left"></i> Volver a Servicios</a>
            </div>
        </div>
    </div>
</main>

<?php 
        include '../includes/footer.php'; 
    } else {
        // No se encontró la solicitud con el ID proporcionado
        include '../includes/header.php';
        echo '<div class="container" style="text-align: center; padding: 50px 0;">
                <h1>Error</h1>
                <p>No se encontró la solicitud con el ID especificado.</p>
                <a href="servicios.php" class="btn-volver" style="display: inline-block; margin-top: 20px;">Volver a Servicios</a>
              </div>';
        include '../includes/footer.php';
    }
} else {
    // No se proporcionó un ID de solicitud
    include '../includes/header.php';
    echo '<div class="container" style="text-align: center; padding: 50px 0;">
            <h1>Error</h1>
            <p>No se ha especificado una solicitud de servicio.</p>
            <a href="servicios.php" class="btn-volver" style="display: inline-block; margin-top: 20px;">Volver a Servicios</a>
          </div>';
    include '../includes/footer.php';
}
?>

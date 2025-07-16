<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$success = true;
$messages = [];

try {
    // Crear base de datos y tablas si no existen
    $sql_file = '../config/estructura bd DSVII.sql';
    if (file_exists($sql_file)) {
        $sql = file_get_contents($sql_file);
        
        // Ejecutar las consultas SQL
        if ($conn->multi_query($sql)) {
            do {
                // Almacenar los resultados de la primera consulta
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->next_result());
        }
    }
    
    // Insertar categorías de ejemplo
    $categorias = [
        ['Diseño Gráfico', 'Creación de identidades visuales y material gráfico', 'fa-palette'],
        ['Desarrollo Web', 'Diseño y desarrollo de sitios web profesionales', 'fa-laptop-code'],
        ['Marketing Digital', 'Estrategias digitales para promoción y ventas', 'fa-bullhorn'],
        ['Branding', 'Desarrollo de marca e identidad corporativa', 'fa-star'],
        ['UX/UI Design', 'Diseño de experiencia e interfaz de usuario', 'fa-mobile-alt']
    ];
    
    foreach ($categorias as $categoria) {
        $stmt = $conn->prepare("INSERT IGNORE INTO Categorias (nombre_categoria, descripcion, icono) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $categoria[0], $categoria[1], $categoria[2]);
        $stmt->execute();
        $stmt->close();
    }
    $messages[] = "Categorías insertadas correctamente.";
    
    // Insertar servicios de ejemplo
    $servicios = [
        ['Diseño de Logotipos', 'Creación de logotipos únicos y memorables para tu marca', 250.00, 180, 1],
        ['Diseño de Brochures', 'Diseño de folletos publicitarios atractivos y profesionales', 150.00, 120, 1],
        ['Sitio Web Corporativo', 'Desarrollo de sitio web profesional con diseño responsivo', 800.00, 480, 2],
        ['Tienda Online', 'Creación de tienda virtual con carrito de compras integrado', 1200.00, 720, 2],
        ['Campaña en Redes Sociales', 'Gestión completa de campañas publicitarias en redes sociales', 400.00, 240, 3],
        ['SEO y Posicionamiento', 'Optimización para motores de búsqueda y mejora de ranking', 350.00, 300, 3],
        ['Manual de Marca', 'Desarrollo completo de identidad visual y manual de uso', 500.00, 360, 4],
        ['Rediseño de Identidad', 'Renovación completa de la imagen corporativa existente', 700.00, 480, 4],
        ['Prototipo de App Móvil', 'Diseño de interfaz y experiencia para aplicaciones móviles', 600.00, 300, 5],
        ['Usability Testing', 'Pruebas de usabilidad y optimización de experiencia de usuario', 300.00, 180, 5]
    ];
    
    foreach ($servicios as $servicio) {
        $stmt = $conn->prepare("INSERT IGNORE INTO Servicios (nombre_servicio, descripcion, precio_base, duracion_estimada, id_categoria) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdii", $servicio[0], $servicio[1], $servicio[2], $servicio[3], $servicio[4]);
        $stmt->execute();
        $stmt->close();
    }
    $messages[] = "Servicios insertados correctamente.";
    
    // Crear usuario administrador por defecto
    $admin_email = 'admin@pixelperfect.com';
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $admin_name = 'Administrador PixelPerfect';
    
    $stmt = $conn->prepare("INSERT IGNORE INTO Usuarios (correo_usuario, pass_usuario, nombre_completo, estado_usuario) VALUES (?, ?, ?, 'activo')");
    $stmt->bind_param("sss", $admin_email, $admin_password, $admin_name);
    if ($stmt->execute()) {
        $messages[] = "Usuario administrador creado: admin@pixelperfect.com / admin123";
    }
    $stmt->close();
    
    // Crear algunos usuarios de ejemplo
    $usuarios_ejemplo = [
        ['cliente1@email.com', 'Juan Pérez', '555-1234'],
        ['cliente2@email.com', 'María González', '555-5678'],
        ['cliente3@email.com', 'Carlos Rodríguez', '555-9012']
    ];
    
    foreach ($usuarios_ejemplo as $usuario) {
        $password = password_hash('123456', PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT IGNORE INTO Usuarios (correo_usuario, pass_usuario, nombre_completo, telefono, estado_usuario) VALUES (?, ?, ?, ?, 'activo')");
        $stmt->bind_param("ssss", $usuario[0], $password, $usuario[1], $usuario[2]);
        $stmt->execute();
        $stmt->close();
    }
    $messages[] = "Usuarios de ejemplo creados (contraseña: 123456).";
    
    // Insertar algunas solicitudes de ejemplo
    $stmt = $conn->prepare("SELECT id_usuario FROM Usuarios WHERE correo_usuario != 'admin@pixelperfect.com' LIMIT 3");
    $stmt->execute();
    $usuarios_result = $stmt->get_result();
    $usuarios_ids = [];
    while ($row = $usuarios_result->fetch_assoc()) {
        $usuarios_ids[] = $row['id_usuario'];
    }
    $stmt->close();
    
    if (!empty($usuarios_ids)) {
        $solicitudes_ejemplo = [
            [1, 1, 'pendiente', 'media', 'Necesito un logo moderno para mi startup tecnológica'],
            [2, 3, 'confirmado', 'alta', 'Quiero crear una tienda online para mi negocio de ropa'],
            [3, 5, 'en_proceso', 'media', 'Necesito ayuda con el SEO de mi sitio web actual']
        ];
        
        foreach ($solicitudes_ejemplo as $index => $solicitud) {
            if (isset($usuarios_ids[$index])) {
                $stmt = $conn->prepare("INSERT IGNORE INTO SolicitudServicio (id_usuario, id_servicio, estado, prioridad, notas_cliente, direccion_servicio, telefono_contacto) VALUES (?, ?, ?, ?, ?, 'Dirección de ejemplo', '555-0000')");
                $stmt->bind_param("iisss", $usuarios_ids[$index], $solicitud[0], $solicitud[2], $solicitud[3], $solicitud[4]);
                $stmt->execute();
                $stmt->close();
            }
        }
        $messages[] = "Solicitudes de ejemplo creadas.";
    }
    
} catch (Exception $e) {
    $success = false;
    $messages[] = "Error: " . $e->getMessage();
}

// Mostrar página de resultados
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicialización de Base de Datos - PixelPerfect</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="init-container">
        <div class="init-content">
            <div class="init-header">
                <h1><i class="fas fa-database"></i> Inicialización de Base de Datos</h1>
                <p>PixelPerfect - Sistema de Gestión</p>
            </div>
            
            <div class="init-results">
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <h2><i class="fas fa-check-circle"></i> ¡Inicialización Exitosa!</h2>
                        <p>La base de datos ha sido configurada correctamente con datos de ejemplo.</p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <h2><i class="fas fa-exclamation-triangle"></i> Error en la Inicialización</h2>
                        <p>Hubo problemas durante la configuración de la base de datos.</p>
                    </div>
                <?php endif; ?>
                
                <div class="messages-list">
                    <h3>Detalles del Proceso:</h3>
                    <ul>
                        <?php foreach ($messages as $message): ?>
                            <li><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($message); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="credentials-info">
                    <h3><i class="fas fa-key"></i> Credenciales de Acceso:</h3>
                    <div class="credential-box">
                        <h4>Administrador:</h4>
                        <p><strong>Email:</strong> admin@pixelperfect.com</p>
                        <p><strong>Contraseña:</strong> admin123</p>
                    </div>
                    
                    <div class="credential-box">
                        <h4>Usuarios de Ejemplo:</h4>
                        <p><strong>Email:</strong> cliente1@email.com, cliente2@email.com, cliente3@email.com</p>
                        <p><strong>Contraseña:</strong> 123456</p>
                    </div>
                </div>
                
                <div class="init-actions">
                    <a href="../index.php" class="cta-button">
                        <i class="fas fa-home"></i> Ir al Sitio Principal
                    </a>
                    <a href="dashboard.php" class="cta-button secondary">
                        <i class="fas fa-tachometer-alt"></i> Panel de Administración
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .init-container {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .init-content {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            width: 100%;
            overflow: hidden;
        }
        
        .init-header {
            background: var(--primary-color);
            color: var(--secondary-color);
            padding: 2rem;
            text-align: center;
        }
        
        .init-header h1 {
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }
        
        .init-results {
            padding: 2rem;
        }
        
        .messages-list {
            margin: 2rem 0;
            background: var(--secondary-color);
            padding: 1.5rem;
            border-radius: 8px;
        }
        
        .messages-list h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .messages-list ul {
            list-style: none;
            padding: 0;
        }
        
        .messages-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--secondary-dark);
        }
        
        .messages-list li:last-child {
            border-bottom: none;
        }
        
        .credentials-info {
            background: var(--secondary-color);
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
        }
        
        .credentials-info h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .credential-box {
            background: white;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            border-left: 3px solid var(--accent-color);
        }
        
        .credential-box h4 {
            color: var(--accent-color);
            margin-bottom: 0.5rem;
        }
        
        .init-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            .init-container {
                padding: 1rem;
            }
            
            .init-actions {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>

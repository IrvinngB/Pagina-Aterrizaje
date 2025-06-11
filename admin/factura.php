<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $servicio = htmlspecialchars($_POST['servicio']);
    $plan = htmlspecialchars($_POST['plan']);
    $mensaje = htmlspecialchars($_POST['mensaje']);
    $fecha = date('d/m/Y');
    $hora = date('H:i:s');
    $numeroFactura = 'FAC-' . date('Ymd') . '-' . rand(1000, 9999);
    
    // Consultamos el precio del plan desde el JSON (simulado aquí)
    $precio = '???.??'; // Valor por defecto
    $moneda = 'USD';
    
    // Intentar obtener el precio real desde datos.json
    $jsonFile = 'data/datos.json';
    if (file_exists($jsonFile)) {
        $datos = file_get_contents($jsonFile);
        $servicios = json_decode($datos, true);
        
        if (is_array($servicios)) {
            foreach ($servicios as $s) {
                if ($s['servicio'] === $servicio) {
                    foreach ($s['planes'] as $p) {
                        if ($p['nombre'] === $plan) {
                            $precio = $p['precio'];
                            $moneda = $p['moneda'];
                            break 2;
                        }
                    }
                }
            }
        }
    }
    
    echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - PixelPerfect</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="shortcut icon" href="./img/LogoColorFinal.svg" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
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
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="./img/LogoColorFinal.svg" alt="Logo de PixelPerfect" />
                <span class="logo-text">
                    <span class="pixel">Pixel</span><span class="perfect">Perfect</span>
                </span>
            </div>
            <button class="hamburger" aria-label="Abrir menú">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="servicios.html">Servicios</a></li>
                <li><a href="nosotros.html">Sobre Nosotros</a></li>
                <li><a href="contacto.html">Contacto</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <div class="factura-container">
            <div class="factura-header">
                <div class="factura-logo">
                    <img src="./img/LogoColorFinal.svg" alt="Logo de PixelPerfect" />
                    <div>
                        <div class="logo-text">
                            <span class="pixel">Pixel</span><span class="perfect">Perfect</span>
                        </div>
                        <small>Soluciones innovadoras para tu negocio</small>
                    </div>
                </div>
                
                <div class="factura-info">
                    <div class="factura-numero">Factura #' . $numeroFactura . '</div>
                    <div class="factura-fecha">Fecha: ' . $fecha . ' - Hora: ' . $hora . '</div>
                </div>
            </div>
            
            <div class="factura-section">
                <h2><i class="fas fa-user"></i> Datos del Cliente</h2>
                <table style="width:100%">
                    <tr>
                        <td><strong>Nombre:</strong></td>
                        <td>' . $nombre . '</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>' . $email . '</td>
                    </tr>
                    <tr>
                        <td><strong>Teléfono:</strong></td>
                        <td>' . $telefono . '</td>
                    </tr>
                </table>
            </div>
            
            <div class="factura-section">
                <h2><i class="fas fa-clipboard-list"></i> Detalle del Servicio</h2>
                <table class="factura-table">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Plan</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>' . $servicio . '</td>
                            <td>' . $plan . '</td>
                            <td>$' . $precio . ' ' . $moneda . '</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="factura-total">Total:</td>
                            <td>$' . $precio . ' ' . $moneda . '</td>
                        </tr>
                    </tfoot>
                </table>
            </div>';
            
            if (!empty($mensaje)) {
                echo '<div class="factura-section">
                    <h2><i class="fas fa-comment"></i> Mensaje del Cliente</h2>
                    <p>' . nl2br($mensaje) . '</p>
                </div>';
            }
            
            echo '<div class="factura-footer">
                <p>¡Gracias por confiar en PixelPerfect!</p>
                <p>Para más información, contáctanos en info@pixelperfect.com</p>
                <div class="factura-actions">
                    <button onclick="window.print()" class="btn-imprimir"><i class="fas fa-print"></i> Imprimir</button>
                    <a href="servicios.html" class="btn-volver"><i class="fas fa-arrow-left"></i> Volver a Servicios</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3 class="logo">PIXEL <span class="color">PERFECT</span></h3>
                <p>Soluciones innovadoras para el crecimiento de tu negocio</p>
            </div>
            <div class="footer-section">
                <h3>Contacto</h3>
                <p><i class="fas fa-envelope"></i> info@PixelPerfect.com</p>
                <p><i class="fas fa-phone"></i> (123) 456-7890</p>
            </div>
            <div class="footer-section">
                <h3>Síguenos</h3>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <p class="copyright">&copy; 2024 PixelPerfect. Todos los derechos reservados.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>';
} else {
    header('Location: servicios.html');
    exit();
}
?>

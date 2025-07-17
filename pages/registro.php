<?php
require_once '../includes/functions.php';

// Si el usuario ya está logueado, redirigir
if (isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

// Procesar el formulario de registro
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once '../config/database.php';
    
    $nombre = sanitizeInput($_POST['nombre']);
    $correo = sanitizeInput($_POST['correo']);
    $telefono = sanitizeInput($_POST['telefono']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validaciones
    if (empty($nombre) || empty($correo) || empty($password) || empty($confirm_password)) {
        $error = 'Por favor, complete todos los campos obligatorios';
    } elseif (!validateEmail($correo)) {
        $error = 'Por favor, ingrese un correo electrónico válido';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres';
    } else {
        // Verificar si el correo ya está registrado
        $stmt = $conn->prepare("SELECT id_usuario FROM Usuarios WHERE correo_usuario = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'El correo electrónico ya está registrado';
        } else {
            // Encriptar contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertar nuevo usuario
            $stmt = $conn->prepare("INSERT INTO Usuarios (correo_usuario, pass_usuario, nombre_completo, telefono) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $correo, $hashed_password, $nombre, $telefono);
            
            if ($stmt->execute()) {
                $success = 'Registro exitoso. Ahora puede iniciar sesión.';
            } else {
                $error = 'Error al registrar: ' . $conn->error;
            }
        }
        
        $stmt->close();
    }
    
    $conn->close();
}

// No incluimos el header en las páginas de registro
$pageTitle = 'Registro de Usuario';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelPerfect - <?php echo $pageTitle; ?></title>
    <link rel="shortcut icon" href="../assets/images/LogoColorFinal.svg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/styles.new.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background-color: aliceblue;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .auth-container {
            width: 100%;
            max-width: 550px;
            padding: 2rem;
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-logo img {
            height: 80px;
        }
        .auth-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 2rem;
        }
        .auth-title {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: var(--accent-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(233, 78, 26, 0.2);
        }
        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        .btn-submit {
            background-color: var(--accent-color);
            color: white;
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: var(--accent-dark);
        }
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
        }
        .auth-footer a {
            color: var(--accent-color);
            text-decoration: none;
        }
        .auth-footer a:hover {
            text-decoration: underline;
            color: var(--accent-dark);
        }
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        .alert-danger {
            background-color: #ffe0e0;
            color: #d32f2f;
            border: 1px solid #ffcdd2;
        }
        .alert-success {
            background-color: #e0f7ea;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }
        .col-2 {
            flex: 0 0 50%;
            padding: 0 0.5rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-logo">
            <img src="../assets/images/LogoColorFinal.svg" alt="PixelPerfect Logo">
        </div>
        <div class="auth-card">
            <h2 class="auth-title">Registro de Usuario</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                    <div class="form-text">Los correos con dominio @pixel.com tienen permisos de administrador.</div>
                </div>
                <div class="form-group">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono">
                </div>
                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-text">Mínimo 8 caracteres</div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-submit">Registrarse</button>
            </form>
            
            <div class="auth-footer">
                <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
</body>
</html>

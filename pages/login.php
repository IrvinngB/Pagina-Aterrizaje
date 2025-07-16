<?php
require_once '../includes/functions.php';

// Si el usuario ya está logueado, redirigir
if (isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

// Procesar el formulario
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once '../config/database.php';
    
    $correo = sanitizeInput($_POST['correo']);
    $password = $_POST['password'];
    
    if (empty($correo) || empty($password)) {
        $error = 'Por favor, complete todos los campos';
    } else {
        // Verificar credenciales en la base de datos
        $stmt = $conn->prepare("SELECT id_usuario, correo_usuario, pass_usuario, nombre_completo FROM Usuarios WHERE correo_usuario = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verificar contraseña
            if (password_verify($password, $user['pass_usuario'])) {
                // Inicio de sesión exitoso
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user_email'] = $user['correo_usuario'];
                $_SESSION['user_name'] = $user['nombre_completo'];
                
                // Verificar si es administrador
                $isAdmin = ($user['correo_usuario'] === 'admin@pixelperfect.com') || isCompanyEmail($user['correo_usuario']);
                $_SESSION['is_admin'] = $isAdmin;
                if ($isAdmin) {
                    $_SESSION['user_role'] = 'admin';
                }
                
                // Redirigir según el tipo de usuario
                if ($isAdmin) {
                    header('Location: ../admin/dashboard.php');
                } else {
                    header('Location: ../index.php');
                }
                exit;
            } else {
                $error = 'Contraseña incorrecta';
            }
        } else {
            $error = 'El correo electrónico no está registrado';
        }
        
        $stmt->close();
    }
    
    $conn->close();
}

// No incluimos el header en las páginas de login
$pageTitle = 'Iniciar Sesión';
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
            max-width: 450px;
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
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-logo">
            <img src="../assets/images/LogoColorFinal.svg" alt="PixelPerfect Logo">
        </div>
        <div class="auth-card">
            <h2 class="auth-title">Iniciar Sesión</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-submit">Iniciar Sesión</button>
            </form>
            
            <div class="auth-footer">
                <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
</body>
</html>

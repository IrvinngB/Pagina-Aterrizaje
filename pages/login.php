<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Página de Ventas</title>
    <style>
        :root {
            --primary-color: #114093; /* Azul oscuro */
            --secondary-color: #f2f6f9; /* Azul muy claro o blanco */
            --accent-color: #e94e1a; /* Naranja brillante */
            --text-color: #333; /* Gris oscuro */
            --background-color: #ffffff; /* Blanco puro */
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: var(--secondary-color);
            border: 1px solid var(--primary-color);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 24px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #cc3f15;
        }

        .error {
            color: var(--accent-color);
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>
    <?php if (isset($_GET['error'])): ?>
        <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <form action="procesar_login.php" method="post">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <input type="submit" value="Entrar">
    </form>
</div>

</body>
</html>

<?php
// Script para inicializar la base de datos con usuarios de ejemplo
require_once '../config/database.php';

// Crear tablas si no existen (usando el archivo SQL)
$sql_file = file_get_contents('../config/estructura bd DSVII.sql');
$statements = explode(';', $sql_file);

foreach ($statements as $statement) {
    if (trim($statement) != '') {
        $conn->query($statement);
    }
}

echo "Estructura de base de datos creada correctamente.<br>";

// Insertar usuarios de ejemplo
$users = [
    // Administrador de la empresa
    [
        'correo' => 'admin@pixel.com',
        'password' => 'admin123',  // En producción, usar contraseñas más seguras
        'nombre' => 'Administrador Principal',
        'telefono' => '123-456-7890'
    ],
    // Empleado de la empresa
    [
        'correo' => 'soporte@pixel.com',
        'password' => 'soporte123',
        'nombre' => 'Técnico de Soporte',
        'telefono' => '123-456-7891'
    ],
    // Cliente normal
    [
        'correo' => 'cliente@ejemplo.com',
        'password' => 'cliente123',
        'nombre' => 'Cliente Ejemplo',
        'telefono' => '123-456-7892'
    ]
];

$insertados = 0;

foreach ($users as $user) {
    // Verificar si el usuario ya existe
    $stmt = $conn->prepare("SELECT id_usuario FROM Usuarios WHERE correo_usuario = ?");
    $stmt->bind_param("s", $user['correo']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        // Usuario no existe, insertarlo
        $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO Usuarios (correo_usuario, pass_usuario, nombre_completo, telefono) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $user['correo'], $hashed_password, $user['nombre'], $user['telefono']);
        
        if ($stmt->execute()) {
            echo "Usuario creado: " . $user['correo'] . "<br>";
            $insertados++;
        } else {
            echo "Error al crear usuario: " . $user['correo'] . " - " . $conn->error . "<br>";
        }
    } else {
        echo "El usuario ya existe: " . $user['correo'] . "<br>";
    }
}

echo "<br>Proceso finalizado. Se insertaron " . $insertados . " usuarios nuevos.";
echo "<br><br><a href='/Laboratorio 3/pages/login.php'>Ir a la página de login</a>";

$conn->close();
?>

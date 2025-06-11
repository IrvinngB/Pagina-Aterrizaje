<?php
require_once '../includes/functions.php';
requireAdmin();

// Título de la página
$pageTitle = 'Panel de administración';
include '../includes/header.php';
?>

<div class="container my-4">
    <h1>Panel de Administración</h1>    <div class="alert" style="background-color: var(--secondary-color); color: var(--primary-color); border: 1px solid var(--primary-light);">
        <p>Bienvenido, administrador <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Gestión de Usuarios</h5>
                    <p class="card-text">Administrar usuarios registrados en el sistema.</p>
                    <a href="/Laboratorio 3/admin/usuarios.php" class="btn" style="background-color: var(--primary-color); color: white; font-weight: bold;">Ir a Usuarios</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Gestión de Servicios</h5>
                    <p class="card-text">Administrar los servicios ofrecidos.</p>
                    <a href="/Laboratorio 3/admin/servicios.php" class="btn" style="background-color: var(--primary-color); color: white; font-weight: bold;">Ir a Servicios</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Solicitudes</h5>
                    <p class="card-text">Ver y gestionar solicitudes de servicios.</p>
                    <a href="/Laboratorio 3/admin/solicitudes.php" class="btn" style="background-color: var(--primary-color); color: white; font-weight: bold;">Ver Solicitudes</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Facturación</h5>
                    <p class="card-text">Gestionar facturas y pagos.</p>
                    <a href="/Laboratorio 3/admin/factura.php" class="btn" style="background-color: var(--primary-color); color: white; font-weight: bold;">Ir a Facturación</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Mensajes de Contacto</h5>
                    <p class="card-text">Ver y responder mensajes de contacto.</p>
                    <a href="/Laboratorio 3/admin/mensajes.php" class="btn" style="background-color: var(--primary-color); color: white; font-weight: bold;">Ver Mensajes</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Configuración</h5>
                    <p class="card-text">Configurar el sistema.</p>
                    <a href="/Laboratorio 3/admin/config.php" class="btn" style="background-color: var(--primary-color); color: white; font-weight: bold;">Configuración</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

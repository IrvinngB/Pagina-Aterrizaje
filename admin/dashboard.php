<?php
require_once '../includes/functions.php';
requireAdmin();

// Título de la página
$pageTitle = 'Panel de administración';
include '../includes/header.php';
?>

<main class="container-fluid p-0">
    <section class="admin-welcome">
        <div class="admin-header">
            <h1><i class="fas fa-tachometer-alt"></i> Panel de Administración</h1>
            <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! Desde aquí puedes gestionar todos los aspectos del sistema.</p>
        </div>
    </section>

    <section id="admin-modules">
        <div class="admin-grid">
            <div class="admin-card">
                <div class="admin-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="admin-content">
                    <h3>Gestión de Usuarios</h3>
                    <p>Administrar usuarios registrados en el sistema.</p>
                    <a href="/Laboratorio 3/admin/usuarios.php" class="cta-button">Ir a Usuarios</a>
                </div>
            </div>
            
            <div class="admin-card">
                <div class="admin-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="admin-content">
                    <h3>Gestión de Servicios</h3>
                    <p>Administrar los servicios ofrecidos.</p>
                    <a href="/Laboratorio 3/admin/servicios.php" class="cta-button">Ir a Servicios</a>
                </div>
            </div>
            
            <div class="admin-card">
                <div class="admin-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="admin-content">
                    <h3>Solicitudes</h3>
                    <p>Ver y gestionar solicitudes de servicios.</p>
                    <a href="/Laboratorio 3/admin/solicitudes.php" class="cta-button">Ver Solicitudes</a>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="admin-content">
                    <h3>Facturación</h3>
                    <p>Gestionar facturas y pagos.</p>
                    <a href="/Laboratorio 3/admin/factura.php" class="cta-button">Ir a Facturación</a>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <div class="admin-content">
                    <h3>Mensajes de Contacto</h3>
                    <p>Ver y responder mensajes de contacto.</p>
                    <a href="/Laboratorio 3/admin/mensajes.php" class="cta-button">Ver Mensajes</a>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <div class="admin-content">
                    <h3>Configuración</h3>
                    <p>Configurar el sistema.</p>
                    <a href="/Laboratorio 3/admin/config.php" class="cta-button">Configuración</a>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .admin-welcome {
        background-color: var(--primary-color);
        color: var(--secondary-color);
        padding: 3rem 0;
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .admin-header {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .admin-header h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--secondary-color);
    }
    
    .admin-header h1 i {
        margin-right: 0.5rem;
        color: var(--accent-color);
    }
    
    .admin-header p {
        font-size: 1.2rem;
        margin-bottom: 0;
    }
    
    #admin-modules {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
        margin-bottom: 3rem;
    }
    
    .admin-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }
    
    .admin-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
    }
    
    .admin-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    
    .admin-icon {
        background-color: var(--primary-color);
        color: white;
        padding: 2rem;
        text-align: center;
        font-size: 3rem;
    }
    
    .admin-content {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .admin-content h3 {
        color: var(--primary-color);
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }
    
    .admin-content p {
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }
    
    .cta-button {
        display: inline-block;
        background-color: var(--accent-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    
    .cta-button:hover {
        background-color: var(--accent-dark);
        color: white;
    }
    
    @media (max-width: 768px) {
        .admin-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php include '../includes/footer.php'; ?>

<?php
require_once '../includes/functions.php';
requireAdmin();

$pageTitle = 'Panel de administración';
include '../includes/header.php';
?>

<main class="container-fluid p-0">
    <section class="admin-hero">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <h1><i class="fas fa-tachometer-alt"></i> Panel de Administración</h1>
            <p>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>! Desde aquí puedes gestionar todos los aspectos del sistema.</p>
            <div class="admin-stats">
                <div class="quick-stat">
                    <i class="fas fa-users"></i>
                    <span>Usuarios Activos</span>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Solicitudes Pendientes</span>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-envelope"></i>
                    <span>Mensajes Nuevos</span>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-modules">
        <div class="container">
            <div class="section-header">
                <h2>Módulos de Administración</h2>
                <p>Accede rápidamente a todas las funciones del sistema</p>
            </div>
            
            <div class="admin-grid">
                <div class="admin-card">
                    <div class="card-header">
                        <div class="admin-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-badge">
                            <span>Activo</span>
                        </div>
                    </div>
                    <div class="admin-content">
                        <h3>Gestión de Usuarios</h3>
                        <p>Administrar usuarios registrados, permisos y roles del sistema.</p>
                        <div class="card-features">
                            <span class="feature">Crear usuarios</span>
                            <span class="feature">Asignar roles</span>
                            <span class="feature">Gestionar permisos</span>
                        </div>
                        <a href="usuarios.php" class="card-button">
                            <span>Ir a Usuarios</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="admin-card">
                    <div class="card-header">
                        <div class="admin-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="card-badge">
                            <span>Activo</span>
                        </div>
                    </div>
                    <div class="admin-content">
                        <h3>Gestión de Categorías</h3>
                        <p>Administrar las categorías de servicios y organizar el contenido.</p>
                        <div class="card-features">
                            <span class="feature">Crear categorías</span>
                            <span class="feature">Organizar servicios</span>
                            <span class="feature">Gestionar jerarquías</span>
                        </div>
                        <a href="categorias.php" class="card-button">
                            <span>Ir a Categorías</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="admin-card">
                    <div class="card-header">
                        <div class="admin-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="card-badge">
                            <span>Activo</span>
                        </div>
                    </div>
                    <div class="admin-content">
                        <h3>Gestión de Servicios</h3>
                        <p>Administrar los servicios ofrecidos, precios y descripciones.</p>
                        <div class="card-features">
                            <span class="feature">Crear servicios</span>
                            <span class="feature">Gestionar precios</span>
                            <span class="feature">Actualizar contenido</span>
                        </div>
                        <a href="servicios.php" class="card-button">
                            <span>Ir a Servicios</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="admin-card">
                    <div class="card-header">
                        <div class="admin-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="card-badge urgent">
                            <span>Urgente</span>
                        </div>
                    </div>
                    <div class="admin-content">
                        <h3>Solicitudes</h3>
                        <p>Ver y gestionar solicitudes de servicios de los clientes.</p>
                        <div class="card-features">
                            <span class="feature">Revisar solicitudes</span>
                            <span class="feature">Cambiar estados</span>
                            <span class="feature">Comunicar con clientes</span>
                        </div>
                        <a href="solicitudes.php" class="card-button">
                            <span>Ver Solicitudes</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

             

                <div class="admin-card">
                    <div class="card-header">
                        <div class="admin-icon">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div class="card-badge new">
                            <span>Nuevo</span>
                        </div>
                    </div>
                    <div class="admin-content">
                        <h3>Mensajes de Contacto</h3>
                        <p>Ver y responder mensajes de contacto de los visitantes.</p>
                        <div class="card-features">
                            <span class="feature">Leer mensajes</span>
                            <span class="feature">Responder consultas</span>
                            <span class="feature">Archivar conversaciones</span>
                        </div>
                        <a href="/Laboratorio 3/admin/mensajes.php" class="card-button">
                            <span>Ver Mensajes</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="card-header">
                        <div class="admin-icon">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <div class="card-badge">
                            <span>Sistema</span>
                        </div>
                    </div>
                    <div class="admin-content">
                        <h3>Configuración</h3>
                        <p>Configurar parámetros del sistema y preferencias generales.</p>
                        <div class="card-features">
                            <span class="feature">Configurar sistema</span>
                            <span class="feature">Gestionar preferencias</span>
                            <span class="feature">Backup y seguridad</span>
                        </div>
                        <a href="/Laboratorio 3/admin/config.php" class="card-button">
                            <span>Configuración</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</main>

<style>
:root {
    --primary-color: #114093;
    --secondary-color: #f2f6f9;
    --accent-color: #e94e1a;
    --text-color: #333;
    --background-color: #ffffff;
    --primary-light: #2a5bb7;
    --primary-dark: #0c2f6e;
    --accent-light: #ff6a3c;
    --accent-dark: #c93f12;
    --secondary-dark: #d1dce8;
}

.admin-hero {
    position: relative;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    padding: 4rem 0;
    text-align: center;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, var(--primary-color), var(--primary-light));
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(17, 64, 147, 0.1);
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.hero-content h1 {
    font-size: clamp(2.5rem, 5vw, 3.5rem);
    margin-bottom: 1rem;
    font-weight: 700;
}

.hero-content h1 i {
    color: var(--accent-color);
    margin-right: 1rem;
}

.hero-content p {
    font-size: 1.3rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.admin-stats {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.quick-stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 25px;
    backdrop-filter: blur(10px);
}

.quick-stat i {
    color: var(--accent-color);
    font-size: 1.2rem;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.admin-modules {
    padding: 6rem 0;
    background: var(--secondary-color);
}

.section-header {
    text-align: center;
    margin-bottom: 4rem;
}

.section-header h2 {
    color: var(--primary-color);
    font-size: clamp(2rem, 4vw, 2.5rem);
    margin-bottom: 1rem;
    font-weight: 700;
}

.section-header p {
    color: var(--text-color);
    font-size: 1.2rem;
    opacity: 0.8;
}

.admin-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
    gap: 2rem;
}

.admin-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
}

.admin-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem 2rem 0;
}

.admin-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}

.card-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.card-badge span {
    color: white;
}

.card-badge:not(.urgent):not(.new) {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.card-badge.urgent {
    background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
}

.card-badge.new {
    background: linear-gradient(135deg, #6f42c1 0%, #8e44ad 100%);
}

.admin-content {
    padding: 1.5rem 2rem 2rem;
}

.admin-content h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.admin-content p {
    color: var(--text-color);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.card-features {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 2rem;
}

.feature {
    background: var(--secondary-dark);
    color: var(--primary-color);
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
}

.card-button {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    width: 100%;
    justify-content: center;
}

.card-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(233, 78, 26, 0.3);
    color: white;
}

.admin-summary {
    padding: 4rem 0;
    background: white;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.summary-card {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 2rem;
    background: var(--secondary-color);
    border-radius: 15px;
    transition: transform 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-5px);
}

.summary-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.summary-content h3 {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.summary-content p {
    color: var(--text-color);
    opacity: 0.8;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.summary-link {
    color: var(--accent-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
}

.summary-link:hover {
    color: var(--accent-dark);
}

@media (max-width: 768px) {
    .admin-grid {
        grid-template-columns: 1fr;
    }
    
    .admin-stats {
        flex-direction: column;
        align-items: center;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
    }
    
    .summary-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<?php include '../includes/footer.php'; ?>

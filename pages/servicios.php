<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

$pageTitle = 'Servicios';
include '../includes/header.php';

// Obtener servicios activos de la base de datos
$sql = "SELECT s.*, c.nombre_categoria, c.icono 
        FROM Servicios s 
        LEFT JOIN Categorias c ON s.id_categoria = c.id_categoria 
        WHERE s.activo = 1 
        ORDER BY c.nombre_categoria, s.nombre_servicio";
$result = $conn->query($sql);
?>

<style>
.services-container {
    background: #ffffff;
    min-height: 100vh;
    padding: 4rem 0;
}

.services-header {
    text-align: center;
    margin-bottom: 4rem;
}

.services-title {
    font-size: 3.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
}

.services-subtitle {
    color: #666666;
    font-size: 1.2rem;
    font-weight: 400;
    max-width: 600px;
    margin: 0 auto;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.service-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    border: 1px solid #e0e0e0;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    height: fit-content;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.service-card:hover {
    background: #f8f9fa;
    transform: translateY(-4px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    border-color: #d0d0d0;
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.service-card:hover::before {
    opacity: 1;
}

.service-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: var(--accent-color);
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 16px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.service-category {
    color: #666666;
    font-size: 0.85rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.service-name {
    color: #333333;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.8rem;
    line-height: 1.3;
}

.service-pricing {
    margin-bottom: 1.5rem;
}

.service-price {
    color: var(--primary-color);
    font-size: 2rem;
    font-weight: 900;
    margin-bottom: 0.3rem;
    background: #fff;
    border-radius: 18px;
    padding: 0.5rem 1.5rem;
    display: inline-block;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.service-price-old {
    color: #666666;
    font-size: 1rem;
    text-decoration: line-through;
    margin-left: 0.5rem;
}

.service-duration {
    color: #666666;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.service-features {
    list-style: none;
    padding: 0;
    margin: 0 0 2rem 0;
}

.service-features li {
    color: #444444;
    font-size: 0.95rem;
    margin-bottom: 0.8rem;
    display: flex;
    align-items: flex-start;
    gap: 0.8rem;
    line-height: 1.5;
}

.service-features li::before {
    content: '✓';
    color: var(--accent-color);
    font-weight: 700;
    font-size: 1rem;
    margin-top: 0.1rem;
}

.service-button {
    width: 100%;
    padding: 1rem;
    border-radius: 50px;
    border: none;
    font-weight: 700;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: block;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.service-button.primary {
    background: var(--accent-color);
    color: white;
}

.service-button.primary:hover {
    background: #d4621a;
    transform: scale(1.02);
}

.service-button.secondary {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.service-button.secondary:hover {
    background: var(--primary-color);
    color: white;
    transform: scale(1.02);
}

.service-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.5s ease;
}

.service-button:hover::before {
    left: 100%;
}

.no-services {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    color: #666666;
}

.no-services p {
    font-size: 1.2rem;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .services-title {
        font-size: 2.5rem;
    }
    
    .services-grid {
        grid-template-columns: 1fr;
        padding: 0 1rem;
    }
    
    .service-card {
        padding: 1.25rem;
    }
}
</style>

<main class="services-container">
    <div class="services-header">
        <h1 class="services-title">Nuestros Servicios</h1>
        <p class="services-subtitle">Descubre la experiencia perfecta para ti</p>
    </div>
    
    <div class="services-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($servicio = $result->fetch_assoc()): ?>
                <div class="service-card">
                    <?php if (!empty($servicio['oferta'])): ?>
                        <div class="service-badge">
                            <?php echo htmlspecialchars($servicio['oferta']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="service-category">
                        <?php echo htmlspecialchars($servicio['nombre_categoria'] ?? 'General'); ?>
                    </div>
                    
                    <h3 class="service-name">
                        <?php echo htmlspecialchars($servicio['nombre_servicio']); ?>
                    </h3>
                    
                    <div class="service-pricing">
                        <div class="service-price">
                            <?php if ($servicio['precio_base']): ?>
                                $<?php echo number_format($servicio['precio_base'], 2); ?>
                                <?php if (!empty($servicio['precio_oferta'])): ?>
                                    <span class="service-price-old">$<?php echo number_format($servicio['precio_oferta'], 2); ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color: #666666; font-size: 1.2rem;">Consultar</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($servicio['duracion_estimada']): ?>
                            <div class="service-duration">
                                <i class="fas fa-clock"></i>
                                <?php echo $servicio['duracion_estimada']; ?> min
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <ul class="service-features">
                        <?php 
                            $features = array_filter(explode("\n", $servicio['descripcion']));
                            foreach ($features as $feature):
                                if (trim($feature) !== ''):
                        ?>
                            <li><?php echo htmlspecialchars(trim($feature)); ?></li>
                        <?php 
                                endif;
                            endforeach;
                        ?>
                    </ul>
                    
                    <div class="service-actions">
                        <?php if (isLoggedIn()): ?>
                            <a href="solicitar_servicio.php?id=<?php echo $servicio['id_servicio']; ?>" 
                               class="service-button primary">
                                Comenzar
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="service-button secondary">
                                Iniciar Sesión para Solicitar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-services">
                <p>No hay servicios disponibles en este momento.</p>
                <p>Vuelve pronto para descubrir nuevas opciones.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
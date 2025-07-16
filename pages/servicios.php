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

<main class="container-fluid p-0">
    <br>
    <section id="servicios">
        <h1>Nuestros Servicios</h1>
        <div class="service-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($servicio = $result->fetch_assoc()): ?>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas <?php echo htmlspecialchars($servicio['icono'] ?? 'fa-cog'); ?>"></i>
                        </div>
                        <div class="service-content">
                            <h3><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></h3>
                            <p class="service-category"><?php echo htmlspecialchars($servicio['nombre_categoria'] ?? 'General'); ?></p>
                            <p class="service-description"><?php echo htmlspecialchars($servicio['descripcion']); ?></p>
                            <?php if ($servicio['precio_base']): ?>
                                <div class="service-price">
                                    Desde $<?php echo number_format($servicio['precio_base'], 2); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($servicio['duracion_estimada']): ?>
                                <div class="service-duration">
                                    <i class="fas fa-clock"></i> <?php echo $servicio['duracion_estimada']; ?> min
                                </div>
                            <?php endif; ?>
                            <div class="service-actions">
                                <?php if (isLoggedIn()): ?>
                                    <a href="solicitar_servicio.php?id=<?php echo $servicio['id_servicio']; ?>" 
                                       class="cta-button">Solicitar Servicio</a>
                                <?php else: ?>
                                    <a href="login.php" class="cta-button">Iniciar Sesión para Solicitar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-services">
                    <p>No hay servicios disponibles en este momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>                
</main>

<?php include '../includes/footer.php'; ?>

<?php
require_once '../includes/functions.php';
$pageTitle = 'Nosotros';
include '../includes/header.php';
?>

<main class="container-fluid p-0">
    <section class="about-hero">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <h1><i class="fas fa-users"></i> Sobre Nosotros</h1>
            <p>Conoce más sobre PixelPerfect y nuestro compromiso con la excelencia</p>
        </div>
    </section>
    
    <section class="about-story">
        <div class="container">
            <div class="story-grid">
                <div class="story-content">
                    <div class="story-section">
                        <h2>Nuestra Historia</h2>
                        <p>PixelPerfect nació de la pasión por crear soluciones visuales excepcionales que ayuden a las empresas a destacar en un mundo digital cada vez más competitivo. Fundada en 2024, nuestra agencia se ha consolidado como un referente en diseño gráfico y desarrollo web.</p>
                        <p>Nos especializamos en transformar ideas en realidades visuales impactantes, trabajando pixel a pixel para alcanzar la perfección que nuestros clientes merecen.</p>
                    </div>
                    
                    <div class="story-section">
                        <h2>Nuestra Misión</h2>
                        <p>Impulsar el crecimiento de tu empresa con estrategias personalizadas de diseño gráfico y desarrollo web, creando identidades visuales únicas que conecten con tu audiencia y generen resultados tangibles.</p>
                    </div>
                    
                    <div class="story-section">
                        <h2>Nuestra Visión</h2>
                        <p>Ser la agencia de diseño líder reconocida por nuestra innovación, creatividad y resultados excepcionales, ayudando a empresas de todos los tamaños a alcanzar su máximo potencial visual.</p>
                    </div>
                </div>
                
                <div class="story-image">
                    <div class="image-container">
                        <img src="../assets/images/space1.jpg" alt="Equipo PixelPerfect trabajando" class="about-img">
                        <div class="image-overlay">
                            <div class="overlay-content">
                                <i class="fas fa-play-circle"></i>
                                <span>Ver nuestro proceso</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="values-section">
        <div class="container">
            <div class="section-header">
                <h2>Nuestros Valores</h2>
                <p>Los principios que guían cada proyecto que desarrollamos</p>
            </div>
            
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="value-content">
                        <h3>Innovación</h3>
                        <p>Siempre buscamos nuevas formas de resolver problemas y crear soluciones únicas que marquen la diferencia en el mercado.</p>
                    </div>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="value-content">
                        <h3>Pasión</h3>
                        <p>Amamos lo que hacemos y esa pasión se refleja en cada proyecto que desarrollamos para nuestros clientes.</p>
                    </div>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="value-content">
                        <h3>Excelencia</h3>
                        <p>No nos conformamos con menos que la perfección. Cada pixel cuenta en nuestra búsqueda de la excelencia.</p>
                    </div>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="value-content">
                        <h3>Colaboración</h3>
                        <p>Trabajamos mano a mano con nuestros clientes, creando partnerships duraderos basados en la confianza.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="team-section">
        <div class="container">
            <div class="section-header">
                <h2>Nuestro Equipo</h2>
                <p>Profesionales apasionados por el diseño y la innovación</p>
            </div>
            
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-photo">
                        <img src="../assets/images/pixel2.jpg" alt="Director Creativo">
                        <div class="member-overlay">
                            <div class="social-links">
                                <a href="#"><i class="fab fa-linkedin"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="member-info">
                        <h3>Alex Rodriguez</h3>
                        <p class="member-role">Director Creativo</p>
                        <p>Con más de 10 años de experiencia en diseño gráfico, Alex lidera nuestro equipo creativo con visión innovadora.</p>
                    </div>
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="../assets/images/pixel3.png" alt="Desarrolladora Web">
                        <div class="member-overlay">
                            <div class="social-links">
                                <a href="#"><i class="fab fa-linkedin"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="member-info">
                        <h3>Maria González</h3>
                        <p class="member-role">Desarrolladora Web Senior</p>
                        <p>Especialista en tecnologías web modernas, Maria convierte diseños en experiencias digitales excepcionales.</p>
                    </div>
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="../assets/images/space2.jpg" alt="Diseñador UX/UI">
                        <div class="member-overlay">
                            <div class="social-links">
                                <a href="#"><i class="fab fa-linkedin"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="member-info">
                        <h3>Carlos Mendez</h3>
                        <p class="member-role">Diseñador UX/UI</p>
                        <p>Experto en experiencia de usuario, Carlos se asegura de que cada interfaz sea intuitiva y atractiva.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header">
                <h2>Lo que Dicen Nuestros Clientes</h2>
                <p>Testimonios reales de clientes satisfechos</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial">
                    <div class="testimonial-content">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p>"PixelPerfect transformó completamente nuestra imagen corporativa. Su atención al detalle y creatividad superaron todas nuestras expectativas."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <h4>María Rodríguez</h4>
                            <span>CEO de TechSolutions</span>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial">
                    <div class="testimonial-content">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p>"El equipo de PixelPerfect es altamente profesional y dedicado. Entregaron nuestro proyecto a tiempo y con una calidad excepcional."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <h4>Juan Pérez</h4>
                            <span>Director de Marketing en InnovaCorp</span>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial">
                    <div class="testimonial-content">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p>"Su enfoque colaborativo y creatividad nos ayudaron a destacar en nuestro mercado. Recomendamos PixelPerfect sin dudarlo."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <h4>Ana Martínez</h4>
                            <span>Fundadora de StartupVision</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="stats-section">
        <div class="container">
            <div class="section-header">
                <h2>Números que Hablan</h2>
                <p>Resultados que demuestran nuestro compromiso</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="stat-number">150+</div>
                    <div class="stat-label">Proyectos Completados</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Clientes Satisfechos</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-number">5</div>
                    <div class="stat-label">Años de Experiencia</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="stat-number">99%</div>
                    <div class="stat-label">Satisfacción del Cliente</div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>¿Listo para Trabajar con Nosotros?</h2>
                <p>Convierte tu visión en realidad con nuestro equipo de expertos</p>
                <a href="contacto.php" class="cta-button primary">
                    <span>Iniciar Proyecto</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
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

.about-hero {
    position: relative;
    height: 60vh;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    text-align: center;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('../assets/images/space1.jpg') center/cover;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(17, 64, 147, 0.9) 0%, rgba(42, 91, 183, 0.8) 100%);
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
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
    opacity: 0.9;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.about-story {
    padding: 6rem 0;
    background: white;
}

.story-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.story-section {
    margin-bottom: 3rem;
}

.story-section:last-child {
    margin-bottom: 0;
}

.story-section h2 {
    color: var(--primary-color);
    font-size: 2rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.story-section p {
    color: var(--text-color);
    line-height: 1.8;
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

.image-container {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.about-img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(17, 64, 147, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-container:hover .image-overlay {
    opacity: 1;
}

.image-container:hover .about-img {
    transform: scale(1.05);
}

.overlay-content {
    text-align: center;
    color: white;
}

.overlay-content i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--accent-color);
}

.overlay-content span {
    font-size: 1.2rem;
    font-weight: 600;
}

.values-section {
    padding: 6rem 0;
    background: var(--secondary-color);
}

.section-header {
    text-align: center;
    margin-bottom: 4rem;
}

.section-header h2 {
    font-size: clamp(2rem, 4vw, 2.5rem);
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 700;
}

.section-header p {
    font-size: 1.2rem;
    color: var(--text-color);
    opacity: 0.8;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.value-card {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    text-align: center;
}

.value-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.value-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
}

.value-content h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.value-content p {
    color: var(--text-color);
    line-height: 1.6;
}

.team-section {
    padding: 6rem 0;
    background: white;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 3rem;
}

.team-member {
    text-align: center;
}

.member-photo {
    position: relative;
    width: 250px;
    height: 250px;
    margin: 0 auto 2rem;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.member-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.member-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(17, 64, 147, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.member-photo:hover .member-overlay {
    opacity: 1;
}

.member-photo:hover img {
    transform: scale(1.1);
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-links a {
    width: 50px;
    height: 50px;
    background: var(--accent-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.social-links a:hover {
    transform: scale(1.1);
}

.member-info h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.member-role {
    color: var(--accent-color);
    font-weight: 600;
    margin-bottom: 1rem;
}

.member-info p {
    color: var(--text-color);
    line-height: 1.6;
}

.testimonials-section {
    padding: 6rem 0;
    background: var(--secondary-color);
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.testimonial {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.testimonial:hover {
    transform: translateY(-5px);
}

.testimonial-content {
    margin-bottom: 2rem;
}

.quote-icon {
    color: var(--accent-color);
    font-size: 2rem;
    margin-bottom: 1rem;
}

.testimonial-content p {
    color: var(--text-color);
    line-height: 1.6;
    font-style: italic;
    font-size: 1.1rem;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.author-avatar {
    width: 60px;
    height: 60px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.author-info h4 {
    color: var(--primary-color);
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.author-info span {
    color: var(--text-color);
    opacity: 0.8;
    font-size: 0.9rem;
}

.stats-section {
    padding: 6rem 0;
    background: var(--primary-color);
    color: white;
}

.stats-section .section-header h2,
.stats-section .section-header p {
    color: white;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    text-align: center;
}

.stat-item {
    padding: 2rem;
}

.stat-icon {
    font-size: 3rem;
    color: var(--accent-color);
    margin-bottom: 1rem;
}

.stat-number {
    font-size: 3.5rem;
    font-weight: 800;
    color: var(--accent-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1.2rem;
    opacity: 0.9;
}

.cta-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, var(--secondary-color) 0%, white 100%);
    text-align: center;
}

.cta-content h2 {
    color: var(--primary-color);
    font-size: clamp(2rem, 4vw, 2.5rem);
    margin-bottom: 1rem;
    font-weight: 700;
}

.cta-content p {
    font-size: 1.2rem;
    color: var(--text-color);
    margin-bottom: 2rem;
    opacity: 0.8;
}

.cta-button {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.cta-button.primary {
    background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
    color: white;
    box-shadow: 0 8px 25px rgba(233, 78, 26, 0.3);
}

.cta-button.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(233, 78, 26, 0.4);
    color: white;
}

@media (max-width: 768px) {
    .story-grid {
        grid-template-columns: 1fr;
        gap: 3rem;
    }
    
    .values-grid {
        grid-template-columns: 1fr;
    }
    
    .team-grid {
        grid-template-columns: 1fr;
    }
    
    .testimonials-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<?php include '../includes/footer.php'; ?>

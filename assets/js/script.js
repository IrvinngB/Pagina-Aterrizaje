// Menú de hamburguesa
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');

if (hamburger && navLinks) {
    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        hamburger.classList.toggle('active');
    });

    // Cerrar el menú al hacer clic en un enlace
    navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('active');
            hamburger.classList.remove('active');
        });
    });
}

// Control de audio
document.addEventListener('DOMContentLoaded', function() {
    const audioElement = document.getElementById('background-audio');
    const muteButton = document.getElementById('mute-button');
    const muteIcon = muteButton ? muteButton.querySelector('i') : null;

    if (audioElement && muteButton && muteIcon) {
        // Iniciar el audio en silencio
        audioElement.muted = true;

        muteButton.addEventListener('click', function() {
            audioElement.muted = !audioElement.muted;

            // Cambiar el ícono según el estado
            if (audioElement.muted) {
                muteIcon.className = 'fas fa-volume-mute';
            } else {
                muteIcon.className = 'fas fa-volume-up';
                // Intentar reproducir el audio (puede requerir interacción del usuario en algunos navegadores)
                audioElement.play().catch(error => {
                    console.log('Error al reproducir audio:', error);
                });
            }
        });
    }
});

// Smooth scrolling para los enlaces de navegación
const smoothAnchors = document.querySelectorAll('a[href^="#"]');
if (smoothAnchors.length > 0) {
    smoothAnchors.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href && href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });
}

// Manejar el envío del formulario de contacto
const contactForm = document.getElementById('contactForm');

// Validación del formulario de contacto
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(contactForm);
        const name = formData.get('name');
        const email = formData.get('email');
        const message = formData.get('message');

        if (!name || !email || !message) {
            alert('Por favor, completa todos los campos.');
            return;
        }

        // Aquí deberías implementar la lógica real de envío del formulario
        console.log('Formulario enviado:', { name, email, message });

        // Simulación de envío exitoso
        alert('¡Gracias por tu mensaje! Te contactaremos pronto.');
        contactForm.reset();
    });
}

// Animación de aparición al hacer scroll
const animateOnScroll = () => {
    const elements = document.querySelectorAll('.service, .testimonial');
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;
        if (elementTop < windowHeight - 50) {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }
    });
};

window.addEventListener('scroll', animateOnScroll);
window.addEventListener('load', animateOnScroll);

// Cargar servicios y planes desde JSON y mostrarlos en #php-servicios
const contenedorServicios = document.getElementById('php-servicios');
if (contenedorServicios) {
    contenedorServicios.innerHTML = '<div class="cargando">Cargando servicios...</div>';
    fetch('data/datos.json')
        .then(response => {
            if (!response.ok) throw new Error('No se pudo cargar el archivo de servicios');
            return response.json();
        })
        .then(servicios => {
            if (!Array.isArray(servicios) || servicios.length === 0) {
                contenedorServicios.innerHTML = '<p style="color:#e74c3c">No hay servicios disponibles en este momento.</p>';
                return;
            }
            
            // Ordenar servicios alfabéticamente por nombre
            servicios.sort((a, b) => a.servicio.localeCompare(b.servicio));
              // Dividir los servicios en grupos de 3
            const gruposDeServicios = [];
            for (let i = 0; i < servicios.length; i += 3) {
                gruposDeServicios.push(servicios.slice(i, i + 3));
            }
            
            let html = '';
            
            // Generar HTML para cada grupo de servicios
            gruposDeServicios.forEach((grupo, grupoIndex) => {
                html += `<div class="grupo-servicios">`;
                
                // Crear los servicios dentro de este grupo
                grupo.forEach(servicio => {
                    // Generar un id base para los planes de este servicio
                    const idBase = servicio.servicio.toLowerCase().replace(/[^a-z0-9]+/g, '-');
                    
                    html += `<div class="servicio">
                        <h2>${servicio.servicio}</h2>
                        <p>${servicio.descripcion}</p>
                        <div class="planes" style="display: flex; gap: 15px; justify-content: space-between;">`;
                    
                    // Ordenar planes alfabéticamente por nombre
                    servicio.planes.sort((a, b) => a.nombre.localeCompare(b.nombre));
                    
                    // Agregar cada plan
                    servicio.planes.forEach(plan => {
                        const planId = `${idBase}-plan-${plan.nombre.toLowerCase().replace(/[^a-z0-9]+/g, '-')}`;
                        html += `<div class="plan" id="${planId}" style="flex: 1; max-width: 32%; display: flex; flex-direction: column;">
                            <div style="flex-grow: 1;">
                                <h3>${plan.nombre}</h3>
                                <p class="precio">Precio: $${plan.precio} ${plan.moneda}</p>
                                <strong>Incluye:</strong>
                                <ul>${plan.incluye.map(item => `<li>${item}</li>`).join('')}</ul>
                                <strong>Requisitos:</strong>
                                <ul>${plan.requisitos.map(req => `<li>${req}</li>`).join('')}</ul>
                            </div>
                            <form action="form.html" method="get" style="margin-top: auto;">
                                <input type="hidden" name="servicio" value="${servicio.servicio}">
                                <input type="hidden" name="plan" value="${plan.nombre}">
                                <button type="submit" class="btn-solicitar">Solicitar</button>
                            </form>
                        </div>`;
                    });
                    
                    html += `</div></div>`;
                });
                
                html += `</div>`;
            });
            
            contenedorServicios.innerHTML = html;
            
            // Aplicar estilos para tamaños uniformes de tarjetas después de cargar contenido
            igualaTamanioTarjetas();
            
            setTimeout(animateOnScroll, 100);
        })
        .catch(error => {
            contenedorServicios.innerHTML = '<p style="color:#e74c3c">No se pudieron cargar los servicios.</p>';
            console.error('Error cargando servicios:', error);
        });
}

// Función para igualar tamaños de tarjetas de planes
function igualaTamanioTarjetas() {
    // Para cada grupo de servicios
    document.querySelectorAll('.grupo-servicios').forEach(grupo => {
        // Igualar altura de los servicios
        const servicios = grupo.querySelectorAll('.servicio');
        let maxAlturaServicio = 0;
        
        servicios.forEach(servicio => {
            servicio.style.height = 'auto';
            const altura = servicio.offsetHeight;
            maxAlturaServicio = Math.max(maxAlturaServicio, altura);
        });
        
        // Aplicar altura máxima a todos los servicios del grupo
        servicios.forEach(servicio => {
            servicio.style.minHeight = maxAlturaServicio + 'px';
        });
        
        // Igualar la altura del contenido interno de cada plan (la parte superior sin el botón)
        servicios.forEach(servicio => {
            const contenidosPlanes = servicio.querySelectorAll('.plan > div:first-child');
            let maxAlturaContenido = 0;
            
            contenidosPlanes.forEach(contenido => {
                contenido.style.height = 'auto';
                const altura = contenido.offsetHeight;
                maxAlturaContenido = Math.max(maxAlturaContenido, altura);
            });
            
            // Aplicar altura máxima al contenido de todos los planes
            contenidosPlanes.forEach(contenido => {
                contenido.style.minHeight = maxAlturaContenido + 'px';
            });
            
            // También igualar altura externa de los planes
            const planes = servicio.querySelectorAll('.plan');
            let maxAlturaPlan = 0;
            
            planes.forEach(plan => {
                plan.style.height = 'auto';
                const altura = plan.offsetHeight;
                maxAlturaPlan = Math.max(maxAlturaPlan, altura);
            });
            
            // Aplicar altura máxima a todos los planes del servicio
            planes.forEach(plan => {
                plan.style.minHeight = maxAlturaPlan + 'px';
            });
        });
    });
}


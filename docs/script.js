// Menú de hamburguesa
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    hamburger.classList.toggle('active');
});

// Control de audio
document.addEventListener('DOMContentLoaded', function() {
    const audioElement = document.getElementById('background-audio');
    const muteButton = document.getElementById('mute-button');
    const muteIcon = muteButton.querySelector('i');
    
    // Iniciar el audio en silencio
    audioElement.muted = true;
    
    muteButton.addEventListener('click', function() {
        if (audioElement) {
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
        }
    });
});

// Cerrar el menú al hacer clic en un enlace
navLinks.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
        navLinks.classList.remove('active');
        hamburger.classList.remove('active');
    });
});

// Smooth scrolling para los enlaces de navegación
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Manejar el envío del formulario de contacto
const contactForm = document.getElementById('contactForm');

// Validación del formulario de contacto
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




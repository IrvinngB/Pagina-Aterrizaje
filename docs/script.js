(function() {
    // Crear el contenedor del widget
    const widgetContainer = document.createElement('div');
    widgetContainer.className = 'widget-container';

    // Crear el iframe del widget
    const iframe = document.createElement('iframe');
    iframe.id = 'chatWidget';
    iframe.src = 'http://localhost:3000/index.html';
    iframe.style.border = 'none';
    iframe.style.width = '85px';
    iframe.style.height = '85px';
    iframe.style.transition = 'all 0.3s ease';
    iframe.style.borderRadius = '50%'; // Solo el icono es redondo
    iframe.style.overflow = 'hidden';
    iframe.style.position = 'fixed';
    iframe.style.bottom = '20px';
    iframe.style.right = '20px';
    iframe.style.zIndex = '9999';
    iframe.style.minWidth = '80px';
    iframe.style.minHeight = '80px';

    widgetContainer.appendChild(iframe);
    document.body.appendChild(widgetContainer);

    // Agregar el listener para los mensajes
    window.addEventListener('message', (event) => {
        if (!event.data || !event.data.type) return;

        switch (event.data.type) {
            case 'resize':
                iframe.classList.add('expanded');
                iframe.style.width = event.data.width;
                iframe.style.height = event.data.height;
                iframe.style.borderRadius = '0'; // El iframe expandido no es redondo
                break;
                
            case 'reset':
                iframe.classList.remove('expanded');
                iframe.style.width = '80px';
                iframe.style.height = '80px';
                iframe.style.borderRadius = '50%'; // El icono es redondo
                break;
        }
    });
})();

// Menú de hamburguesa
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');

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

contactForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(contactForm);
    const name = formData.get('name');
    const email = formData.get('email');
    const message = formData.get('message');
    
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




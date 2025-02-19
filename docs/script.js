(function() {
    // contenedor del widget
    const widgetContainer = document.createElement('div');
    widgetContainer.className = 'widget-container';

    // iframe del widget
    const iframe = document.createElement('iframe');
    iframe.id = 'chatWidget';
    iframe.src = 'http://localhost:3000/index.html';
    iframe.style.border = 'none';
    iframe.style.transition = 'all 0.3s ease';
    iframe.style.overflow = 'hidden';
    iframe.style.position = 'fixed';
    iframe.style.zIndex = '9999';

    //  tamaño y posición del iframe
    function adjustIframeSize() {
        const isMobile = window.innerWidth <= 600;
        const isLargeScreen = window.innerWidth > 1200;
        
        if (iframe.classList.contains('expanded')) {
            if (isMobile) {
                // Vista móvil
                iframe.style.width = '100%';
                iframe.style.height = '100%';
                iframe.style.bottom = '0';
                iframe.style.right = '0';
                iframe.style.borderRadius = '0';
            } else if (isLargeScreen) {
                // Pantallas grandes
                iframe.style.width = '450px';
                iframe.style.height = '700px';
                iframe.style.bottom = '20px';
                iframe.style.right = '20px';
                iframe.style.borderRadius = '16px';
            } else {
                // Pantallas medianas
                iframe.style.width = '380px';
                iframe.style.height = '600px';
                iframe.style.bottom = '20px';
                iframe.style.right = '20px';
                iframe.style.borderRadius = '12px';
            }
        } else {
            // Icono del widget 
            if (isMobile) {
                iframe.style.width = '60px';
                iframe.style.height = '60px';
                iframe.style.bottom = '10px';
                iframe.style.right = '10px';
            } else if (isLargeScreen) {
                iframe.style.width = '85px';
                iframe.style.height = '85px';
                iframe.style.bottom = '20px';
                iframe.style.right = '20px';
            } else {
                iframe.style.width = '80px';
                iframe.style.height = '80px';
                iframe.style.bottom = '20px';
                iframe.style.right = '20px';
            }
            iframe.style.borderRadius = '50%';
        }
    }

    // Ajustar tamaño inicial
    adjustIframeSize();

    widgetContainer.appendChild(iframe);
    document.body.appendChild(widgetContainer);

    // Agregar el listener para los mensajes
    window.addEventListener('message', (event) => {
        if (!event.data || !event.data.type) return;

        switch (event.data.type) {
            case 'resize':
                iframe.classList.add('expanded');
                adjustIframeSize();
                break;
                
            case 'reset':
                iframe.classList.remove('expanded');
                adjustIframeSize();
                break;
        }
    });

    // Ajustar tamaño cuando cambie el tamaño de la ventana
    window.addEventListener('resize', adjustIframeSize);

    // Pantallas chicas
    function checkIframePosition() {
        if (iframe.classList.contains('expanded')) {
            const rect = iframe.getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;

            if (rect.right > viewportWidth) {
                iframe.style.right = '10px';
            }
            if (rect.bottom > viewportHeight) {
                iframe.style.bottom = '10px';
            }
        }
    }

    window.addEventListener('resize', checkIframePosition);
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




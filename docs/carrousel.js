document.addEventListener('DOMContentLoaded', function () {
    // Obtener los elementos necesarios
    const carouselInner = document.querySelector('.carousel-inner');
    const prevButton = document.querySelector('.carousel-control-prev');
    const nextButton = document.querySelector('.carousel-control-next');
    const items = document.querySelectorAll('.carousel-item');
    let currentIndex = 0;
    const totalItems = items.length;

    // Actualizar el carrusel moviéndolo al índice actual
    const updateCarousel = () => {
        const translateX = -currentIndex * 100; // Mover el carrusel por el ancho del contenedor
        carouselInner.style.transform = `translateX(${translateX}%)`;
    };

    // Evento para el botón 'Anterior'
    prevButton.addEventListener('click', () => {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : totalItems - 1;
        updateCarousel();
    });

    // Evento para el botón 'Siguiente'
    nextButton.addEventListener('click', () => {
        currentIndex = (currentIndex < totalItems - 1) ? currentIndex + 1 : 0;
        updateCarousel();
    });

    // Inicializar el carrusel
    updateCarousel();
});

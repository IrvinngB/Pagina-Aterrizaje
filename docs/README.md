# PixelPerfect - Agencia de Diseño Gráfico

## Descripción
PixelPerfect es un sitio web para una agencia ficticia de diseño gráfico que ofrece servicios de diseño de logotipos, branding, diseño publicitario, diseño web, ilustración digital y más. El sitio está diseñado para mostrar el profesionalismo y la creatividad de la agencia, utilizando elementos visuales atractivos y una experiencia de usuario intuitiva.

## Estructura del Sitio
El sitio consta de 4 páginas principales:

1. **Inicio (index.html)**: Página principal con un video de fondo y servicios destacados.
2. **Servicios (servicios.html)**: Muestra todos los servicios ofrecidos a través de un carrusel interactivo.
3. **Sobre Nosotros (nosotros.html)**: Información sobre la empresa, sus valores y testimonios de clientes.
4. **Contacto (contacto.html)**: Formulario de contacto, información de la empresa y mapa de ubicación.

## Tecnologías Utilizadas
- HTML5
- CSS3 con variables personalizadas
- JavaScript
- Font Awesome para iconos
- Google Fonts (Pixelify Sans)
- Google Maps API para el mapa de ubicación
- Formspree para el procesamiento del formulario de contacto

## Características Destacadas

### Diseño Responsivo
El sitio está completamente adaptado para dispositivos móviles y de escritorio, utilizando media queries para ajustar el diseño según el tamaño de la pantalla.

### Tema Visual Consistente
- **Paleta de colores**: 
  - Azul oscuro (#114093) como color primario
  - Azul claro (#f2f6f9) como color secundario
  - Naranja brillante (#e94e1a) como color de acento
  - Gris oscuro (#333) para el texto principal
  - Blanco puro (#ffffff) para el fondo

- **Tipografía**:
  - Font Pixelify Sans para el logotipo y ciertos elementos destacados
  - Arial y sans-serif para el texto general

### Elementos Interactivos
1. **Menú de navegación responsivo**: Con modo hamburguesa para dispositivos móviles
2. **Carrusel de servicios**: Implementación personalizada en JavaScript
3. **Botones de llamada a la acción**: Diseñados para destacar y guiar al usuario
4. **Animaciones sutiles**: En hover y scrolling para mejorar la experiencia de usuario

## Archivos y Directorios
- **HTML**: 
  - index.html - Página de inicio
  - servicios.html - Página de servicios
  - nosotros.html - Página sobre la empresa
  - contacto.html - Página de contacto

- **CSS**:
  - styles.css - Estilos globales del sitio

- **JavaScript**:
  - script.js - Funcionalidades globales (menú hamburguesa, scroll suave, validación de formulario)
  - carrousel.js - Implementación del carrusel de servicios

- **Imágenes**:
  - /img/ - Directorio con imágenes del sitio, incluyendo el logo

## Personalización
Para modificar el tema visual del sitio, se pueden ajustar las variables CSS en el archivo styles.css:

```css
:root {
    --primary-color: #114093; /* Azul oscuro */
    --secondary-color: #f2f6f9; /* Azul muy claro o blanco */
    --accent-color: #e94e1a; /* Naranja brillante */
    --text-color: #333; /* Gris oscuro */
    --background-color: #ffffff; /* Blanco puro */
}
```

## Funcionalidades JavaScript
- **Menú de hamburguesa**: Se activa en dispositivos móviles
- **Navegación suave**: Desplazamiento suave al hacer clic en enlaces internos
- **Validación de formularios**: Comprueba que los campos requeridos estén completos
- **Carrusel de servicios**: Avanza y retrocede entre diferentes servicios

## Integración de Servicios Externos
- **Formspree**: El formulario de contacto utiliza Formspree para procesar y enviar los mensajes
- **Google Maps**: Se integra un mapa para mostrar la ubicación de la oficina

## Estado del Proyecto
El proyecto está completo y funcional, listo para ser utilizado. Todas las páginas son accesibles y responden correctamente en diferentes dispositivos.

## Licencia
© 2024 PixelPerfect. Todos los derechos reservados.

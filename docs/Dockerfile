FROM php:8.2-apache

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install zip

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos del proyecto
# El problema está aquí - la ruta es incorrecta
# COPY docs/ /var/www/html/

# En su lugar, copia desde la raíz actual
COPY . /var/www/html/

# Asegurarse que Apache puede escribir donde sea necesario
RUN chown -R www-data:www-data /var/www/html

# Exponer puerto 80
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]
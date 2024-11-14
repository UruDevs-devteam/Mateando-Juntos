FROM php:8.0-apache

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Agregar extensión mysqli para PHP
RUN docker-php-ext-install mysqli

# Instalar librerías adicionales necesarias para cURL
RUN apt-get update && apt-get install -y libcurl4-openssl-dev
RUN docker-php-ext-install curl

# Instalar dependencias necesarias para Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer (gestor de dependencias)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Crear composer.json directamente dentro del contenedor
RUN echo "{\"require\": {\"firebase/php-jwt\": \"^5.5\"}}" > /var/www/html/composer.json

# Ejecutar composer install para instalar las dependencias dentro del contenedor
RUN cd /var/www/html && composer install



# Configurar permisos para Apache y habilitar .htaccess
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configurar Apache para permitir .htaccess y redirección
RUN echo '<Directory /var/www/html>' >> /etc/apache2/apache2.conf
RUN echo '    AllowOverride All' >> /etc/apache2/apache2.conf
RUN echo '</Directory>' >> /etc/apache2/apache2.conf

# Exponer el puerto 80 para acceso web
EXPOSE 80

# Configuración para iniciar Apache
CMD ["apache2-foreground"]

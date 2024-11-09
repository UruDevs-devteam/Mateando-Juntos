FROM php:8.0-apache

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Agregar extensión mysqli para PHP
RUN docker-php-ext-install mysqli

# Instalar librerías adicionales necesarias para cURL
RUN apt-get update && apt-get install -y libcurl4-openssl-dev
RUN docker-php-ext-install curl

# Configurar permisos para Apache y habilitar .htaccess
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configurar Apache para permitir .htaccess y redirección
RUN echo '<Directory /var/www/html>' >> /etc/apache2/apache2.conf \
    && echo '    Options Indexes FollowSymLinks' >> /etc/apache2/apache2.conf \
    && echo '    AllowOverride All' >> /etc/apache2/apache2.conf \
    && echo '    Require all granted' >> /etc/apache2/apache2.conf \
    && echo '</Directory>' >> /etc/apache2/apache2.conf

# Exponer el puerto 80
EXPOSE 80

# Iniciar Apache en primer plano
CMD ["apache2-foreground"]

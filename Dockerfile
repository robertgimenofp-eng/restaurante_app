FROM php:8.2-apache

# Instalar extensiones de MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copiar tu código al servidor
COPY . /var/www/html/

# Dar permisos para que Apache pueda leer los archivos
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exponer el puerto 80 (el estándar de Apache)
EXPOSE 80
FROM php:8.2-apache

# 1. Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# 2. Arreglar el error AH00534 (El conflicto de MPM)
# Desactivamos el módulo 'event' y activamos 'prefork' que es el que usa PHP
RUN a2dismod mpm_event && a2enmod mpm_prefork

# 3. Copiar tu código
COPY . /var/www/html/

# 4. Permisos
RUN chown -R www-data:www-data /var/www/html

# 5. Exponer el puerto 80
EXPOSE 80
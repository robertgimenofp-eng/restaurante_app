# Usamos PHP 8.2 con Apache integrado
FROM php:8.2-apache

# Instalamos las extensiones para conectar con MySQL (PDO y Mysqli)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activamos mod_rewrite para que funcionen las rutas amigables del MVC
RUN a2enmod rewrite

# Copiamos tu código al servidor web del contenedor
COPY . /var/www/html/

# Damos permisos al usuario de Apache para evitar errores de escritura
RUN chown -R www-data:www-data /var/www/html
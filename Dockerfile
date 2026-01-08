# 1. Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# 2. Instalamos las extensiones para conectar con la base de datos
# (Vital para que funcione tu clase Database.php)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 3. Activamos el módulo "rewrite" de Apache
# (Obligatorio para que funcione tu MVC y las rutas amigables)
RUN a2enmod rewrite

# 4. Copiamos todos tus archivos al contenedor
COPY . /var/www/html/

# 5. Damos permisos al usuario de Apache
RUN chown -R www-data:www-data /var/www/html
# Añade esto para apagar el motor incorrecto y encender el bueno
# Forzamos la desactivación de módulos conflictivos y activamos prefork
# Desactivamos módulos conflictivos de forma segura y activamos prefork
RUN a2dismod mpm_event || true && \
    a2dismod mpm_worker || true && \
    a2enmod mpm_prefork
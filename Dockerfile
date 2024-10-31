FROM php:8.1-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    nginx

# Instalar extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar extensión ZIP para DOMPDF
RUN apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip

# Obtener Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de PHP
RUN composer install --optimize-autoloader --no-dev

# Instalar dependencias de Node y construir assets
RUN npm install
RUN npm run build

# Generar key si no existe
#RUN php artisan key:generate --force

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configurar Nginx
COPY nginx.conf /etc/nginx/sites-available/default

# Exponer puerto
EXPOSE 80

# Script de inicio
COPY start.sh /start.sh
RUN chmod +x /start.sh


CMD ["/start.sh"]
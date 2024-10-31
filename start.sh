#!/bin/bash

# Esperar si se necesita que la base de datos esté lista (útil en entornos de contenedor)
if [ ! -z "$DB_HOST" ]; then
    until nc -z -v -w30 $DB_HOST ${DB_PORT:-3306}; do
        echo "Esperando a que la base de datos esté disponible..."
        sleep 5
    done
fi

# Limpiar y regenerar cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones si es necesario
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

# Crear storage link si no existe
php artisan storage:link

# Configurar permisos de almacenamiento
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Iniciar PHP-FPM
php-fpm -D

# Iniciar Nginx y mantener el contenedor ejecutándose
nginx -g "daemon off;"
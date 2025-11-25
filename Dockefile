# Imagen base de PHP + Apache
FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libonig-dev \
    libzip-dev

# Extensiones necesarias para Laravel + PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql mbstring zip

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Copiar todo el proyecto
COPY . /var/www/html

WORKDIR /var/www/html

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# Construir assets de Vite (si tienes npm)
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install \
    && npm run build

# Generar APP_KEY
RUN php artisan key:generate

# Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer puerto
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]

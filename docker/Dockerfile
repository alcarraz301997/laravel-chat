FROM php:8.1-fpm-alpine

# Instala las extensiones de PHP necesarias
RUN docker-php-ext-install pdo_mysql mysqli

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www/html/laravel-chat

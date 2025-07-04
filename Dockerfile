FROM php:7.4-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache --update \
        git \
        curl \
        icu-dev \
        oniguruma-dev \
        libxml2-dev \
        zlib-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo pdo_mysql mysqli mbstring xml zip intl gd opcache

# Install Composer (copy binary from official Composer image)
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Set working directory inside the container
WORKDIR /var/www

# Copy the existing application code to the container
COPY . .

# Install PHP dependencies using Composer
RUN composer install --prefer-dist --optimize-autoloader

# Make sure permissions are correct for Laravel
RUN chown -R www-data:www-data /var/www

# Expose default PHP-FPM port
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"] 
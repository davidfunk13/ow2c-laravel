# Stage 1: Build the application
FROM composer:2 as builder

WORKDIR /app
COPY . /app

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Set up the production environment
FROM php:8.1-fpm


# Install PHP extensions
RUN docker-php-ext-install \
    ctype \
    curl \
    dom \
    fileinfo \
    filter \
    hash \
    mbstring \
    openssl \
    pcre \
    pdo \
    session \
    tokenizer \
    xml \
    pdo_mysql

# Copy application code and built dependencies
COPY --from=builder /app /var/www/overwatch-2-companion-api
WORKDIR /var/www/overwatch-2-companion-api

# Set correct permissions
RUN chown -R www-data:www-data /var/www/overwatch-2-companion-api
RUN chown -R www-data:www-data /var/www/overwatch-2-companion-api/storage /var/www/overwatch-2-companion-api/bootstrap/cache

# Expose the default PHP-FPM port (9000)
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

FROM php:8.2-fpm

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy application files
COPY . /var/www/html/

WORKDIR /var/www/html

# Configure permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/attachments \
    && chmod -R 777 /var/www/html/cache

EXPOSE 9000

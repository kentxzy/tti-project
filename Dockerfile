FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev nodejs npm \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Apache configuration
RUN a2enmod rewrite
RUN sed -i 's/80/10000/g' /etc/apache2/ports.conf
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Start script
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 10000
CMD ["/start.sh"]
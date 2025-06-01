FROM php:8.2-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Replace Apache default config to allow .htaccess
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Copy all project files to the web root
COPY . /var/www/html/

# Fix ownership (optional)
RUN chown -R www-data:www-data /var/www/html

# Expose HTTP port
EXPOSE 80

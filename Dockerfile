FROM php:8.2-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Overwrite default site config to allow .htaccess
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Copy all PHP files to Apache web root
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

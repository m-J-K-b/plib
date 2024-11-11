# Use the official PHP 5.6 with Apache image
FROM php:5.6-apache

# Set the working directory
WORKDIR /var/www/html

# Copy Apache configuration and enable mod_rewrite and configuration
COPY ./apache/custom.conf /etc/apache2/conf-available/custom.conf
RUN a2enmod rewrite && a2enconf custom

# Install necessary PHP extensions in a single RUN command
RUN docker-php-ext-install pdo_mysql mysql

# Copy project files (last to optimize caching for layers above)
COPY ./ /var/www/html

# Set file permissions for Apache
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expose port 80 for web access
EXPOSE 80

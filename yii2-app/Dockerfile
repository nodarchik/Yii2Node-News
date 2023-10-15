# Use the official Yii2 PHP image as the base image
FROM yiisoftware/yii2-php:7.3-fpm

# Copy your Yii2 application into the image
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Switch to root user to change permissions
USER root

# Update permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 777 /var/www/html

# Install Composer dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

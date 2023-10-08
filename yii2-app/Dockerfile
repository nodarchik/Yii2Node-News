# Dockerfile for PHP
FROM php:7.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y git unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Change the document root if needed
ENV APACHE_DOCUMENT_ROOT /var/www/html/web

# Update apache configuration
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the app's code into the image
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

FROM php:7.2-apache


RUN apt-get update -y && apt-get install -y  libpng-dev libjpeg-dev libfreetype6-dev zlib1g-dev libzip-dev


# Install PHP extensions
RUN docker-php-ext-install exif
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install gd

# Activate Apache modules
RUN a2enmod rewrite
RUN service apache2 restart

# Set the workdir
WORKDIR /var/www/html

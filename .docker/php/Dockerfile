FROM php:8.3-fpm

RUN docker-php-ext-install pdo_mysql mysqli pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl
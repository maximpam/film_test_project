FROM php:8-fpm
RUN apt-get update && apt-get install -y libpng-dev
RUN apt-get update && apt-get install -y mariadb-client libmagickwand-dev --no-install-recommends
RUN pecl install imagick
RUN docker-php-ext-enable imagick
RUN docker-php-ext-install pdo_mysql
RUN apt-get install -y zlib1g-dev
RUN apt-get install -y libzip-dev
RUN docker-php-ext-install zip
RUN docker-php-ext-install mysqli
RUN apt-get update
RUN apt-get install -y
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

RUN apt-get update && apt-get install -y wget git unzip \
    && pecl install xdebug-3.1.4 \
    && docker-php-ext-enable xdebug



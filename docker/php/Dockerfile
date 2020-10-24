FROM php:7.4-fpm

RUN apt-get update \
&& apt-get install -y \
       git \
       libzip-dev \
       zip \
       libpq-dev \
       openssl \
       libicu-dev \
       unixodbc-dev
RUN docker-php-ext-configure intl

RUN docker-php-ext-install pdo pdo_pgsql zip intl

RUN docker-php-ext-enable intl

RUN curl -s https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

COPY upload-size.ini /usr/local/etc/php/conf.d
COPY settings.ini /usr/local/etc/php/conf.d

WORKDIR /var/www/codered

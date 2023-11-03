FROM php:8.1.23-fpm-bullseye

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install excimer

RUN docker-php-ext-enable excimer

RUN apt-get update

RUN apt-get -y install apt-utils vim
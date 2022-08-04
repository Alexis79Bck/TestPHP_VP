FROM php:7.4-cli

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN apt-get update
RUN apt update

RUN apt autoremove -y

RUN apt-get install -y \
    fish \
    vim \
    libzip-dev \
    zip
RUN docker-php-ext-install zip

RUN apt-get install -y libpng-dev
RUN docker-php-ext-install gd

RUN docker-php-ext-install pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV PATH "$PATH:./vendor/bin"
ENV PATH "$PATH:~/.composer/vendor/bin"

RUN composer global require laravel/installer

RUN apt install -y nodejs npm

WORKDIR /project
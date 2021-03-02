FROM php:7.4-fpm-buster

RUN apt-get update && apt-get install -y

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libpq-dev \
    && docker-php-ext-install \
    zip \
    intl \
    pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Install Symfony cli
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Install xdebug
RUN pecl install xdebug-2.8.1 \
    && docker-php-ext-enable xdebug



#FROM php:7.4-fpm
#
#RUN apt update && apt install -y libpq-dev git zip
#RUN docker-php-ext-install pdo_pgsql
#
## Install Composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#
## Install Symfony cli
#RUN curl -sS https://get.symfony.com/cli/installer | bash
#RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
#
## Install xdebug
#RUN pecl install xdebug-2.8.1 \
#    && docker-php-ext-enable xdebug
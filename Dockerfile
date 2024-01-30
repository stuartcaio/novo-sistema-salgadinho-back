FROM php:8.1-apache

COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

RUN rm /etc/apt/preferences.d/no-debian-php

RUN apt-get update && \
    apt-get install -y libxml2-dev

RUN apt-get update && \
    apt-get install -y libxml2-dev libjpeg-dev
    
# Install Dependencies
RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        sqlite3 \
        memcached \
        libzip-dev \
        libpng-dev \
        libmcrypt-dev \
        zip \
        libcurl4-openssl-dev \
        libedit-dev \
        libssl-dev \
        libonig-dev \
        libpq-dev \
        libxml2-dev \
        xz-utils \
        libsqlite3-dev \
        git \
        vim \
        nano \
        net-tools \
        pkg-config \
        iputils-ping

# Install PHP Extensions
RUN docker-php-ext-install gd \
    bcmath \
    calendar \
    iconv \
    ctype \
    intl \
    xml \
    pcntl \
    mysqli \
    pdo \
    pdo_mysql \
    pgsql \
    pdo_pgsql \
    zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN ln -s $(composer config --global home) /root/composer
RUN rm -rf /tmp/*

RUN chown -R www-data:www-data /var/www/html
# && a2enmod rewrite
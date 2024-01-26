FROM php:8.1-apache

COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

COPY ./php.ini /etc/php/8.1/fpm/php.ini

# Configurar o fuso horário para o padrão brasileiro
RUN apt-get update && apt-get install -y tzdata
ENV TZ=America/Sao_Paulo

RUN rm /etc/apt/preferences.d/no-debian-php

RUN apt-get update && \
    apt-get install -y libxml2-dev
        
# Instalação do módulo GD
RUN apt-get install -y libjpeg-dev libfreetype6-dev libwebp-dev libxpm-dev libfreetype6-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm
RUN docker-php-ext-install gd
    
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

RUN apt-get update -yqq \
    && apt-get install -y --no-install-recommends openssl \
    && sed -i 's,^\(MinProtocol[ ]=\).,\1'TLSv1.0',g' /etc/ssl/openssl.cnf \
    && sed -i 's,^\(CipherString[ ]=\).,\1'DEFAULT@SECLEVEL=1',g' /etc/ssl/openssl.cnf\
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN ln -s $(composer config --global home) /root/composer
RUN rm -rf /tmp/*

# Copiar os certificados para o contêiner
# COPY ./certs/cert.pem /etc/ssl/certs/
# COPY ./certs/privatekey.key /etc/ssl/private/

# Habilitar módulos necessários
# RUN a2enmod ssl
# RUN a2enmod headers

# Copiar o arquivo de configuração do Virtual Host
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html
RUN a2enmod rewrite
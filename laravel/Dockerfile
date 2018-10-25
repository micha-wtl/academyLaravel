FROM php:7.2-fpm


# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        make \
        graphviz \
        curl \
        libz-dev \
        libpq-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libssl-dev \
        libmcrypt-dev \
        libcurl4-openssl-dev \
        pkg-config \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN pecl install mcrypt-1.0.1 && \
    docker-php-ext-enable mcrypt

RUN pecl install xdebug-2.6.0 && \
    docker-php-ext-enable xdebug

RUN apt-get update \
    && apt-get install -y libmagickwand-dev imagemagick \
    && pecl install imagick \
    && docker-php-ext-enable imagick

RUN apt-get update -y \
  && apt-get install -y --no-install-recommends \
    ghostscript \
    libgs-dev \
    tesseract-ocr \
    tesseract-ocr-deu \
    tesseract-ocr-deu-frak \
    zbar-tools \
  && rm -rf /var/lib/apt/lists/*

# Install the PHP mcrypt extention
RUN pecl install mongodb-1.3.0 \
  && docker-php-ext-enable mongodb

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY --chown=www-data:www-data laravel/docker/xdebug.ini /usr/local/etc/php/conf.d/20-xdebug.ini

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

RUN cp ./composer.phar /usr/local/bin/composer

RUN apt-get update && \
    apt-get install -y libicu-dev && \
    docker-php-ext-install intl

RUN apt-get -y install zip unzip libzip-dev && \
    pecl install zip

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN usermod -u 1000 www-data

COPY  --chown=www-data:www-data ./laravel/ /application

COPY ./laravel/docker/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini

USER www-data

EXPOSE 9000
WORKDIR "/application/"
CMD ["./wait-for-it.sh", "db:3306", "--", "./start.sh"]
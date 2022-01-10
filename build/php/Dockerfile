FROM php:8.0-fpm

RUN apt-get update --fix-missing && apt-get install libzip-dev -y \
    && apt-get install -y git \
    && apt-get install -y libpng-dev \
    && apt-get install -y zlib1g-dev libicu-dev g++

RUN apt-get install -y build-essential libssl-dev libpng-dev libjpeg-dev libfreetype6-dev

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd

RUN pecl install redis \
    && pecl install zip \
    && echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini \
    && echo 'log_errors = On' >> /usr/local/etc/php/conf.d/docker-php-error-log.ini \
    && echo "date.timezone = Europe/Amsterdam" > /usr/local/etc/php/conf.d/timezone.ini \
    && docker-php-ext-install pdo pdo_mysql bcmath intl \
    && docker-php-ext-install exif \
    && docker-php-ext-enable redis zip exif

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

RUN curl -L https://cs.symfony.com/download/php-cs-fixer-v2.phar -o php-cs-fixer
RUN chmod a+x php-cs-fixer
RUN mv php-cs-fixer /usr/local/bin/php-cs-fixer

RUN ln -snf /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime

WORKDIR /var/www/html/symfony


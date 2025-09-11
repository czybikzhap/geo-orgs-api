FROM php:8.3.0-fpm

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    libpq-dev \
    libxpm-dev \
    libvpx-dev \
    libzip-dev \
    nano \
    curl \
    acl

# Устанавливаем PHP расширения (каждая команда с новой строки)
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    docker-php-ext-install -j$(nproc) gd intl zip pgsql pdo_pgsql exif sockets && \
    docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ \
        --with-xpm=/usr/lib/x86_64-linux-gnu/

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Настраиваем пользователя приложения
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN groupadd -g ${GROUP_ID} www && \
    useradd -u ${USER_ID} -ms /bin/bash -g www www && \
    mkdir -p /var/www/html && \
    chown -R www:www /var/www

WORKDIR /var/www/html

USER www

# Создаем директории и настраиваем права
RUN mkdir -p storage/logs && \
    chmod -R 775 storage && \
    chown -R www:www storage



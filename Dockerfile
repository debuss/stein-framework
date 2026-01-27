FROM dunglas/frankenphp:1-php8.3-alpine

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN install-php-extensions \
    pcntl \
    opcache \
    pdo_mysql \
    pdo_sqlite \
    gd \
    intl \
    zip \
    xml

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

COPY . .

RUN composer dump-autoload --optimize

ENV SERVER_NAME=:80
ENV FRANKENPHP_CONFIG="worker ./public/worker.php"

EXPOSE 80
#EXPOSE 443
#EXPOSE 443/udp

FROM php:8.3-fpm-alpine

RUN apk add --no-cache libpq git unzip \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS postgresql-dev linux-headers \
    && docker-php-ext-install pdo_pgsql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

RUN { \
        echo "xdebug.mode=debug"; \
        echo "xdebug.start_with_request=yes"; \
        echo "xdebug.client_host=host.docker.internal"; \
        echo "xdebug.client_port=9003"; \
        echo "xdebug.log_level=0"; \
    } > /usr/local/etc/php/conf.d/zz-xdebug.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

COPY . .
RUN composer dump-autoload

CMD ["php-fpm"]
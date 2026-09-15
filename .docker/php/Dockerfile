FROM php:8.3.6-fpm-bookworm

ARG TIMEZONE

ENV APP_ENV=prod \
    APP_DEBUG=0 \
    COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libpq-dev \
        libxslt1-dev \
        libzip-dev \
        unzip \
        tzdata

RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install -j"$(nproc)" \
    pdo_pgsql zip xsl gd intl opcache

RUN rm -rf /var/lib/apt/lists/*

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

WORKDIR /var/www/presentation-portfolio-project-api

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --classmap-authoritative \
    --no-scripts

COPY . .
COPY .docker/php/php.prod.ini /usr/local/etc/php/conf.d/zz-production.ini
COPY .docker/php/scripts/entrypoint.prod.sh /usr/local/bin/entrypoint.prod.sh
COPY .docker/php/scripts/run-project-sync.sh /usr/local/bin/run-project-sync.sh
COPY .docker/php/scripts/scheduler-entrypoint.sh /usr/local/bin/scheduler-entrypoint.sh

RUN touch .env \
    && composer dump-autoload --no-dev --classmap-authoritative \
    && chmod 755 /usr/local/bin/entrypoint.prod.sh \
    && chmod 755 /usr/local/bin/run-project-sync.sh \
    && chmod 755 /usr/local/bin/scheduler-entrypoint.sh \
    && mkdir -p var/cache var/log \
    && chown -R www-data:www-data var

ENTRYPOINT ["/usr/local/bin/entrypoint.prod.sh"]

CMD ["php-fpm", "-F"]

FROM php:8.3.6-fpm-bookworm AS runtime
ARG TIMEZONE

COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt-get update && apt-get install -y \
    acl \
    git \
    gnupg \
    g++ \
    libfreetype6-dev \
    libicu-dev  \
    libjpeg-dev \
    libonig-dev \
    libpng-dev \
    libpq-dev \
    libxslt1-dev \
    libzip-dev \
    openssl \
    procps \
    unzip \
    zlib1g-dev \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN docker-php-ext-configure gd --with-jpeg --with-freetype

RUN docker-php-ext-install \
    pdo pdo_pgsql zip xsl gd intl opcache exif mbstring

RUN pecl install -o -f xdebug \
    && docker-php-ext-enable xdebug

COPY run-project-sync.sh /usr/local/bin/run-project-sync.sh
COPY scheduler-entrypoint.sh /usr/local/bin/scheduler-entrypoint.sh

RUN chmod 755 /usr/local/bin/run-project-sync.sh \
    && chmod 755 /usr/local/bin/scheduler-entrypoint.sh

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version

WORKDIR /var/www/presentation-portfolio-project-api

CMD ["php-fpm", "-F"]

FROM runtime AS scheduler

RUN apt-get update \
    && apt-get install -y --no-install-recommends cron \
    && rm -rf /var/lib/apt/lists/*

COPY crontab /etc/cron.d/crontab

RUN chmod 0644 /etc/cron.d/crontab \
    && crontab /etc/cron.d/crontab \
    && touch /var/log/cron.log /var/log/cron.error.log

CMD ["cron", "-f"]

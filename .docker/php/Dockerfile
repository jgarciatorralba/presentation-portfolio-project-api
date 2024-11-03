FROM php:8.3.13-fpm-bullseye
ARG TIMEZONE

COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt-get update && apt-get install -y \
    acl \
    cron \
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
    supervisor \
    unzip \
    zlib1g-dev \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN docker-php-ext-configure gd --with-jpeg --with-freetype

RUN docker-php-ext-install \
    pdo pdo_pgsql zip xsl gd intl opcache exif mbstring

RUN pecl install -o -f xdebug \
    && docker-php-ext-enable xdebug

COPY crontab /etc/cron.d/crontab
RUN chmod 0644 /etc/cron.d/crontab \
    && crontab /etc/cron.d/crontab \
    && touch /var/log/cron.log \
    && touch /var/log/cron.error.log

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version

WORKDIR /var/www/portfolio-api

CMD ["/usr/bin/supervisord"]

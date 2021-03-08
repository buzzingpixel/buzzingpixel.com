############
# Build base
############
FROM php:8.0.2-fpm as base

# Update bash profile
COPY ./docker/.profile /root/.profile
COPY ./docker/.profile /root/.bashrc

# Install application requirements
RUN apt-get update
RUN apt-get install -my --no-install-recommends libfreetype6-dev
RUN apt-get install -my --no-install-recommends libjpeg62-turbo-dev
RUN apt-get install -my --no-install-recommends libpng-dev
RUN apt-get install -my --no-install-recommends libpq-dev
RUN apt-get install -my --no-install-recommends libpq-dev
RUN apt-get install -my --no-install-recommends libonig-dev
RUN apt-get install -my --no-install-recommends libssl-dev
RUN apt-get install -my --no-install-recommends libwebp-dev
RUN apt-get install -my --no-install-recommends libxml2
RUN apt-get install -my --no-install-recommends libxml2-dev
RUN apt-get install -my --no-install-recommends libxpm-dev
RUN apt-get install -my --no-install-recommends libxpm-dev
RUN apt-get install -my --no-install-recommends libzip-dev
RUN apt-get install -my --no-install-recommends openssl
RUN apt-get install -my --no-install-recommends postgresql
RUN apt-get install -my --no-install-recommends postgresql-contrib
RUN apt-get install -my --no-install-recommends unzip
RUN apt-get install -my --no-install-recommends libbz2-dev
RUN apt-get install -my --no-install-recommends libcurl4
RUN apt-get install -my --no-install-recommends libcurl4-openssl-dev
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-xpm --with-webp
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install bz2
RUN docker-php-ext-install exif
RUN docker-php-ext-install iconv
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pgsql
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install zip
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install intl

# Add base PHP config
COPY ./docker/php/php-custom-config.ini /usr/local/etc/php/conf.d/php-custom-config.ini
COPY ./docker/php/fpm-www.conf /usr/local/etc/php-fpm.d/www.conf

# Add composer
COPY --from=composer:2.0.9 /usr/bin/composer /usr/bin/composer





##################
# Build dev target
##################
FROM base as dev

# Use custom dev config for PHP
COPY ./docker/php/php-config-dev.ini /usr/local/etc/php/conf.d/php-env-config.ini

# Install xdebug
RUN pecl install xdebug-3.0.2;
RUN docker-php-ext-enable xdebug;





###################
# Build prod target
###################
FROM base as prod

# Use custom prod config for PHP
COPY ./docker/php/php-config-prod.ini /usr/local/etc/php/conf.d/php-env-config.ini

# Copy the project files into the image for optimal production performance
COPY . /opt/project


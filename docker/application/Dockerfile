##################
# frontend-builder
##################
FROM --platform=$BUILDPLATFORM node:15.8.0 AS frontend-builder
ARG TARGETPLATFORM
ARG BUILDPLATFORM
RUN echo "I am running on $BUILDPLATFORM, building for $TARGETPLATFORM"

# Set our workdir
WORKDIR /opt/project

# Copy the application into the container
COPY . /opt/project

# Install dependencies
RUN yarn

# Run the frontend build
RUN yarn build-prod


###############
# php:8.0.3-fpm
###############
FROM --platform=$BUILDPLATFORM php:8.0.3-fpm
ARG TARGETPLATFORM
ARG BUILDPLATFORM
RUN echo "I am running on $BUILDPLATFORM, building for $TARGETPLATFORM"

# Set our workdir
WORKDIR /opt/project

# Install required packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    default-mysql-client \
    gnupg \
    zip \
    unzip \
    # Cleanup
    && rm -rf /var/lib/apt/lists/*

#
# s6 overlay init and process supervisor
#
ARG S6_VERSION=v1.22.1.0
ENV S6_BEHAVIOUR_IF_STAGE2_FAILS=2

COPY docker/application/build-scripts /build-scripts
RUN chmod +x /build-scripts/download-s6-overlay-to-tmp.sh && /build-scripts/download-s6-overlay-to-tmp.sh $TARGETPLATFORM $S6_VERSION
# Verify GPG signature - "Just Containers <just-containers@jrjrtech.com>"
RUN tar xvfz /tmp/s6-overlay.tar.gz -C / && rm /tmp/s6-overlay.tar.gz

#
# Additional PHP extensions and configuration
#

RUN apt-get update && apt-get install -y --no-install-recommends \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libpq-dev \
    libpq-dev \
    libonig-dev \
    libssl-dev \
    libwebp-dev \
    libxml2 \
    libxml2-dev \
    libxpm-dev \
    libxpm-dev \
    libzip-dev \
    openssl \
    postgresql \
    postgresql-contrib \
    unzip \
    libbz2-dev \
    libcurl4 \
    libcurl4-openssl-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-jpeg --with-freetype --with-xpm --with-webp \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install bz2 \
    && docker-php-ext-install exif \
    && docker-php-ext-install iconv \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install opcache \
    && docker-php-ext-install pgsql \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install zip \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install intl

#
# Install and configure nginx
#

# Find at http://nginx.org/packages/mainline/debian/pool/nginx/n/nginx/
# and http://nginx.org/packages/mainline/debian/pool/nginx/n/nginx-module-njs/
ARG NGINX_VERSION=1.19.9
ARG NJS_VERSION=0.5.3
ARG PKG_RELEASE=1~buster

RUN apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 573BFD6B3D8FBC641079A6ABABF5BD827BD9BF62 \
    && echo "deb http://nginx.org/packages/mainline/debian/ buster nginx" >> /etc/apt/sources.list \
    && apt-get update && apt-get install -y --no-install-recommends --no-install-suggests \
        ca-certificates \
        nginx=${NGINX_VERSION}-${PKG_RELEASE} \
        nginx-module-xslt=${NGINX_VERSION}-${PKG_RELEASE} \
        nginx-module-geoip=${NGINX_VERSION}-${PKG_RELEASE} \
        nginx-module-image-filter=${NGINX_VERSION}-${PKG_RELEASE} \
        nginx-module-perl=${NGINX_VERSION}-${PKG_RELEASE} \
        nginx-module-njs=${NGINX_VERSION}+${NJS_VERSION}-${PKG_RELEASE} \
        gettext-base \
    # Cleanup
    && rm -rf /var/lib/apt/lists/*

# Send logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80

# Add composer
COPY --from=composer:2.0.12 /usr/bin/composer /usr/bin/composer

# Standard production config
RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

# Custom php config
COPY docker/application/php/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-custom.conf
COPY docker/application/php/php.ini $PHP_INI_DIR/conf.d/

# Install xdebug
RUN pecl install xdebug-3.0.4
COPY docker/application/php/xdebug.ini $PHP_INI_DIR/disabled/

# Install redis
RUN pecl install redis && docker-php-ext-enable redis

# Custom nginx config
COPY docker/application/nginx /etc/nginx/imported
RUN cp /etc/nginx/imported/nginx.conf /etc/nginx/nginx.conf \
    && cp /etc/nginx/imported/default.conf /etc/nginx/conf.d/default.conf

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy the application into the container
COPY . /opt/project

# Copy the front-end build
COPY --from=frontend-builder /opt/project/public/assets/css public/assets/css
COPY --from=frontend-builder /opt/project/public/assets/js public/assets/js

# Install composer dependencies
RUN composer install --no-interaction --no-ansi --no-progress --no-dev --optimize-autoloader

# Container configuration
COPY docker/application/cont-init.d /etc/cont-init.d/
COPY docker/application/services.d /etc/services.d/

# Update bash profile
COPY ./docker/.profile /root/.profile
COPY ./docker/.profile /root/.bashrc

#HEALTHCHECK --interval=1s --timeout=5s --retries=10 CMD curl --fail http://localhost/healthcheck/8HYhJLtmQA || exit 1

ENTRYPOINT ["/init"]

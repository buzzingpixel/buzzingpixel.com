##################
# frontend-builder
##################
FROM --platform=$TARGETPLATFORM node:26.13.1-trixie-slim AS frontend-builder

# Node 26 images no longer bundle Yarn or Corepack
RUN npm install --global yarn@1.22.22

# Set our workdir
WORKDIR /var/www

# Copy the application into the container
COPY . /var/www

# Install dependencies
RUN yarn install --frozen-lockfile

# Run the frontend build
RUN yarn build-prod

###################
# application build
###################
FROM --platform=$TARGETPLATFORM php:8.5.13-fpm-trixie
ARG TARGETPLATFORM
ARG TARGETARCH
ARG BUILDPLATFORM
ARG BUILDARCH

# Set the work directory to where we'll be installing/mounting the codebase
WORKDIR /var/www

#
# s6 overlay init and process supervisor
#
ARG S6_VERSION=v3.2.3.2
ENV S6_BEHAVIOUR_IF_STAGE2_FAILS=2
RUN case ${TARGETARCH} in \
        "amd64")  S6_ARCH=x86_64  ;; \
        "arm64")  S6_ARCH=aarch64  ;; \
    esac \
    && cd /tmp \
    && for TARBALL in s6-overlay-noarch s6-overlay-${S6_ARCH} s6-overlay-symlinks-noarch; do \
        curl -fsSLO https://github.com/just-containers/s6-overlay/releases/download/${S6_VERSION}/${TARBALL}.tar.xz \
        && curl -fsSLO https://github.com/just-containers/s6-overlay/releases/download/${S6_VERSION}/${TARBALL}.tar.xz.sha256 \
        && sha256sum -c ${TARBALL}.tar.xz.sha256 \
        && tar -C / -Jxpf ${TARBALL}.tar.xz \
        && rm ${TARBALL}.tar.xz ${TARBALL}.tar.xz.sha256 \
        || exit 1; \
    done

# Install required packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    gnupg \
    libbz2-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libpq-dev \
    libwebp-dev \
    libxpm-dev \
    libzip-dev \
    unzip \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

#
# Additional PHP extensions and configuration
#

RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-xpm --with-webp \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        bz2 \
        exif \
        gd \
        intl \
        pcntl \
        pdo_pgsql \
        pgsql \
        zip

#
# Install and configure nginx
#

RUN curl -fsSL https://nginx.org/keys/nginx_signing.key \
        | gpg --dearmor -o /usr/share/keyrings/nginx-archive-keyring.gpg \
    && echo "deb [signed-by=/usr/share/keyrings/nginx-archive-keyring.gpg] http://nginx.org/packages/mainline/debian trixie nginx" \
        > /etc/apt/sources.list.d/nginx.list \
    && apt-get update && apt-get install -y --no-install-recommends --no-install-suggests \
        ca-certificates \
        nginx=1.31.10-1~trixie \
        gettext-base \
    # Cleanup
    && rm -rf /var/lib/apt/lists/*

# Send logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80

# Standard production config
RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

# Custom php config
COPY docker/application/php/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-custom.conf
COPY docker/application/php/php.ini $PHP_INI_DIR/conf.d/
COPY docker/application/php/php-dev.ini $PHP_INI_DIR/

# Install redis
RUN pecl install redis && docker-php-ext-enable redis

# Custom nginx config
COPY docker/application/nginx /etc/nginx/imported
RUN cp /etc/nginx/imported/nginx.conf /etc/nginx/nginx.conf \
    && cp /etc/nginx/imported/default.conf /etc/nginx/conf.d/default.conf
COPY ./docker/application/nginx/dhparam.pem /etc/nginx/ssl/dhparam.pem

# Copy the application into the container
COPY . /var/www

# Container configuration
COPY --chmod=755 docker/application/cont-init.d /etc/cont-init.d/
COPY --chmod=755 docker/application/services.d /etc/services.d/

# Update bash profile
COPY ./docker/.profile /root/.profile
COPY ./docker/.profile /root/.bashrc

# Add composer
COPY --from=composer:2.11.2 /usr/bin/composer /usr/bin/composer

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install composer dependencies
RUN composer install --no-interaction --no-ansi --no-progress --no-dev --optimize-autoloader

# Copy the front-end build
COPY --from=frontend-builder /var/www/public/assets/css public/assets/css
COPY --from=frontend-builder /var/www/public/assets/js public/assets/js

# The php-fpm base image stops with SIGQUIT, which s6-overlay v3's /init ignores
STOPSIGNAL SIGTERM

ENTRYPOINT ["/init"]

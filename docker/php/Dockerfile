FROM php:8.5-fpm

RUN set -eux \
	&& DEBIAN_FRONTEND=noninteractive apt-get update -qq \
	&& DEBIAN_FRONTEND=noninteractive apt-get install -qq -y --no-install-recommends --no-install-suggests apt-utils \
	&& DEBIAN_FRONTEND=noninteractive apt-get install -qq -y --no-install-recommends --no-install-suggests \
		alien \
		libevent-dev \
		libcurl4-openssl-dev \
                firebird-dev \
                libaio-dev \
                libbz2-dev \
                libc-client-dev \
		libfbclient2 \
                libfreetype6-dev \
                libgmp-dev \
                libib-util \
                libicu-dev \
                libjpeg-dev \
                libkrb5-dev \
                libldap2-dev \
                libmcrypt-dev \
                libmemcached-dev \
                libpcre3-dev \
                libpng-dev \
                libpq-dev \
                libpspell-dev \
                libsasl2-dev \
                libsnmp-dev \
                libsodium-dev \
                libssl-dev \
                libtidy-dev \
                libvpx-dev \
                libwebp-dev \
                libxml2-dev \
                libxpm-dev \
                libxslt-dev \
                libyaml-dev \
                libzip-dev \
                snmp \
                zlib1g-dev \
                ca-certificates \
                git \
		gettext-base \
		zip

ADD php.ini /usr/local/etc/php/php.ini

RUN CFLAGS="$CFLAGS -D_GNU_SOURCE" docker-php-ext-install sockets

RUN set -eux \
        && pecl install redis \
        && docker-php-ext-enable redis \
        && true

RUN set -eux \
        && pecl install mongodb \
        && docker-php-ext-enable mongodb \
        && true

RUN set -eux \
        && docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) pdo_pgsql \
        && true

RUN set -eux \
        && docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) zip \
        && true

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ADD composer/auth.json /root/.composer/auth.json

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint"]

WORKDIR /var/www/app

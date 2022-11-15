FROM php:8.1-fpm-bullseye

WORKDIR /app

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libicu-dev \
    libonig-dev \
    libzip-dev

RUN docker-php-ext-install pdo pdo_mysql zip intl exif mbstring
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && install-php-extensions http
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY entrypoint.sh /usr/local/share/entrypoint.sh

RUN ["chmod", "+x", "/usr/local/share/entrypoint.sh"]

CMD ["/usr/local/share/entrypoint.sh","php-fpm","-F"]

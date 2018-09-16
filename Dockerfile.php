FROM php:7.2-fpm
# RUN apt-get update && apt-cache search gpgme
RUN apt-get update && apt-get install -y \
    gpg \
    libgpgme11-dev
RUN pecl install gnupg \
    && docker-php-ext-enable gnupg

RUN ln -s /usr/bin/gpg /usr/bin/gpg2



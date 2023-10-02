FROM php:7.4-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends openssl libssl-dev libcurl4-openssl-dev \
    && cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && apt-get clean \
    && docker-php-ext-install mysqli pdo_mysql\
    && rm -rf /var/lib/apt/lists/*
EXPOSE 80 443

COPY index.html /var/www/html
COPY select.php /var/www/html
COPY post.php /var/www/html
COPY style.css /var/www/html

CMD apachectl -D FOREGROUND
# ./Dockerfile
FROM dunglas/frankenphp:alpine

RUN install-php-extensions \
    pdo_mysql \
    intl \
    zip

FROM php:8.3-apache

RUN apt-get update \
  && apt-get install -y libcurl4-openssl-dev \
  && docker-php-ext-install pdo_mysql curl \
  && rm -rf /var/lib/apt/lists/*
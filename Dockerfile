FROM php:7.3-cli
RUN apt-get update && apt-get install -y --no-install-recommends git unzip
RUN pecl install xdebug-2.7.2 && docker-php-ext-enable xdebug
COPY . /code
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /code
EXPOSE 80
CMD [ "php", "-S", "0.0.0.0:80", "-t", "public" ]

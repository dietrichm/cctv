FROM php:7.2-cli
COPY . /code
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /code
EXPOSE 80
CMD [ "php", "-S", "0.0.0.0:80", "-t", "public" ]

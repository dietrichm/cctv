FROM php:7.3-cli
RUN apt-get update && apt-get install -y --no-install-recommends git unzip
RUN pecl install xdebug-2.7.2 && docker-php-ext-enable xdebug
RUN echo 'xdebug.remote_enable=on' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_handler=dbgp' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_connect_back=on' >> /usr/local/etc/php/php.ini
COPY . /code
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /code
EXPOSE 80
CMD [ "php", "-S", "0.0.0.0:80", "-t", "public" ]

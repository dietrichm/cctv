FROM php:7.3-cli
COPY . /code
WORKDIR /code
CMD [ "php", "-S", "0.0.0.0:80" ]

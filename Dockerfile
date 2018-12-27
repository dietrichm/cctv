FROM php:7.2-cli
COPY . /code
WORKDIR /code
EXPOSE 80
CMD [ "php", "-S", "0.0.0.0:80", "-t", "public" ]

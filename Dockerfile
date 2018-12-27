FROM php:7.3-cli
COPY . /code
WORKDIR /code
EXPOSE 80
CMD [ "php", "-S", "0.0.0.0:80" ]

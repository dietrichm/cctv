FROM php:7.3-cli
COPY . /code
WORKDIR /code
CMD [ "php", "./snap.php" ]

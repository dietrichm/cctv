.PHONY: build up composer install test lint deploy

default: up

build:
	docker-compose build

up: install
	docker-compose up -d

composer:
	docker run --rm --interactive --tty --volume $(PWD):/app --user $(shell id -u):$(shell id -g) composer $(filter-out $@,$(MAKECMDGOALS))

install: composer.json composer.lock
	docker run --rm --interactive --tty --volume $(PWD):/app --user $(shell id -u):$(shell id -g) composer install

test:
	docker-compose run --rm cctv vendor/bin/phpunit $(filter-out $@,$(MAKECMDGOALS))

lint:
	docker-compose run --rm --user $(shell id -u):$(shell id -g) cctv vendor/bin/php-cs-fixer fix --verbose

deploy:
	composer install
	rsync -avh --exclude=.git/ --delete-after ./* /var/www/cctv/

%:
	@:

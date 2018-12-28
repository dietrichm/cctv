.PHONY: build up composer install test

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

%:
	@:

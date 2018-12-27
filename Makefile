.PHONY: build up composer

default: up

build:
	docker-compose build

up:
	docker-compose up -d

composer:
	docker run --rm --interactive --tty --volume $(PWD):/app composer $(filter-out $@,$(MAKECMDGOALS))

install: composer.json composer.lock
	docker run --rm --interactive --tty --volume $(PWD):/app composer install

%:
	@:

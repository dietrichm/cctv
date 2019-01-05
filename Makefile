.PHONY: build up down composer test lint vendor-no-dev sync-to-www deploy

user := $(shell id -u):$(shell id -g)

default: up

build:
	docker-compose build

up: vendor .env
	docker-compose up -d

down:
	docker-compose down

composer:
	docker-compose run --rm --user $(user) cctv composer $(filter-out $@,$(MAKECMDGOALS))

vendor: composer.json composer.lock
	docker-compose run --rm --user $(user) cctv composer install

.env:
	cp .env.example .env

test:
	docker-compose run --rm cctv vendor/bin/phpunit $(filter-out $@,$(MAKECMDGOALS))

lint:
	docker-compose run --rm --user $(user) cctv vendor/bin/php-cs-fixer fix --verbose

logs:
	docker-compose logs -f

vendor-no-dev: composer.json composer.lock
	composer install --no-dev

sync-to-www:
	rsync -avh --exclude=.git/ --exclude=tests/ --exclude=.env --delete-after . /var/www/cctv/

deploy: vendor-no-dev sync-to-www

%:
	@:

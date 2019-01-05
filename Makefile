.PHONY: build up down composer test lint logs cache-clear local-cache-clear deploy

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

cache-clear:
	docker-compose exec cctv bin/cache-clear.sh

local-cache-clear:
	sudo env TMP_DIR=/tmp/*-php-fpm.service-*/tmp bin/cache-clear.sh

deploy:
	composer install --no-dev
	rsync -avh --exclude=.git/ --exclude=tests/ --exclude=.env --delete-after . /var/www/cctv/

%:
	@:

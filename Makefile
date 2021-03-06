.PHONY: build up down composer test lint logs console cache-clear deploy

user := $(shell id -u):$(shell id -g)

default: up

build:
	docker-compose build

up: .env
	docker-compose up -d

down:
	docker-compose down

composer:
	docker-compose exec --user $(user) cctv composer $(filter-out $@,$(MAKECMDGOALS))

vendor: composer.json composer.lock
	docker-compose exec --user $(user) cctv composer install

.env:
	cp .env.example .env

test:
	docker-compose exec cctv vendor/bin/phpunit $(filter-out $@,$(MAKECMDGOALS))

lint:
	docker-compose exec --user $(user) cctv vendor/bin/php-cs-fixer fix --verbose

logs:
	docker-compose logs -f

console:
	docker-compose exec cctv bin/console $(filter-out $@,$(MAKECMDGOALS))

cache-clear:
	docker-compose exec cctv bin/cache-clear.sh

deploy:
	composer install --no-dev --optimize-autoloader
	rsync -avh --exclude=.git/ --exclude=tests/ --exclude=.env --delete-after . /var/www/cctv/
	sudo env TMP_DIR=/tmp/*-php-fpm.service-*/tmp bin/cache-clear.sh

%:
	@:

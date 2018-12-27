.PHONY: build up

default: up

build:
	docker-compose build

up:
	docker-compose up -d

.PHONY: build up

default: up

build:
	docker build -t cctv .

up:
	docker run -it --rm -p 80:80 cctv

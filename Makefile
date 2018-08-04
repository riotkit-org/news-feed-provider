#!make

#include .env
#export $(shell sed 's/=.*//' .env)

.SILENT:

SHELL := /bin/bash

## Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

ENV="prod"

## This help dialog
help:
	printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	printf " make [target]\n\n"
	printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	awk '/^[a-zA-Z\-\_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

## Create database structure first time (better use migrations)
setup_database_first_time:
	printf " >> Creating the database"
	./bin/console doctrine:database:create --env=${ENV} 2>/dev/null || true
	./bin/console doctrine:schema:create --env=${ENV}

## Build the application by running preparation tasks such as composer install
build:
	composer install --dev
	php ./bin/console cache:clear --env=${ENV}
	php ./bin/console cache:warmup --env=${ENV}

## Migrate the database
migrate:
	./bin/console doctrine:migrations:migrate -vv -n --env=${ENV}

## Prepare the application to be ready to run
deploy:
	make build
	make migrate

## Build x86_64 image
build@x86_64:
	sudo docker build . -f ./Dockerfile.x86_64 -t wolnosciowiec/news-feed-provider

## Build arm7hf image
build@arm7hf:
	sudo docker build . -f ./Dockerfile.arm7hf -t wolnosciowiec/news-feed-provider:arm7hf

## Push x86_64 image to registry
push@x86_64:
	sudo docker push wolnosciowiec/news-feed-provider

## Push arm7hf image to registry
push@arm7hf:
	sudo docker push wolnosciowiec/news-feed-provider:arm7hf

build_docker_image:
	chown www-data:www-data /var/www/html -R
	mv ./web/app.php ./web/index.php
	su www-data -s /bin/bash -c "cd /var/www/html && make deploy"

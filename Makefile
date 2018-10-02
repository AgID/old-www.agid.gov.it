include .env

.PHONY: up down stop prune ps shell solr-core

default: up

DRUPAL_ROOT ?= /var/www/html/web

dir=$(shell pwd)

up:
	@echo "Starting up containers for $(PROJECT_NAME)..."
	docker-compose --file=docker-compose-local.yml up -d

down: stop

stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	@docker-compose --file=docker-compose-local.yml stop

prune:
	@echo "Removing containers for $(PROJECT_NAME)..."
	@docker-compose --file=docker-compose-local.yml down -v

ps:
	@echo "Running containers for $(PROJECT_NAME)..."
	@docker ps --filter name='$(PROJECT_NAME)*'

shell:
	@docker exec -ti -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") bash

solr-core:
	@docker exec www_agid_gov_it_solr make create core="default" host="localhost" -f /usr/local/bin/actions.mk

build:
	@echo "Building containers for $(PROJECT_NAME)..."
	@docker run --rm -it -v "${dir}/docroot:/app" -w "/app" wodby/drupal-php:${PHP_TAG} composer install --no-interaction --prefer-dist
	@docker build -t $(PROJECT_NAME)-php:$(PROJECT_TAG) -f Dockerfile_php .
	@docker build -t $(PROJECT_NAME)-apache:$(PROJECT_TAG) -f Dockerfile_apache .

push:
	@echo "Pushing containers for $(PROJECT_NAME) to remote repository..."
	@docker PROJECT_TAG $(PROJECT_NAME)-php\:$(PROJECT_TAG) $(DOCKER_ACCOUNT)/$(PROJECT_NAME)-php\:$(PROJECT_TAG)
	@docker push $(DOCKER_ACCOUNT)/$(PROJECT_NAME)-php\:$(PROJECT_TAG)
	@docker PROJECT_TAG $(PROJECT_NAME)-apache\:$(PROJECT_TAG) $(DOCKER_ACCOUNT)/$(PROJECT_NAME)-apache\:$(PROJECT_TAG)
	@docker push $(DOCKER_ACCOUNT)/$(PROJECT_NAME)-apache\:$(PROJECT_TAG)

include .env

.PHONY: up down stop prune ps shell solr-core

default: up

DRUPAL_ROOT ?= /var/www/html/web

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
	docker build -t $(PROJECT_NAME)-php:$(TAG) -f Dockerfile_php .
	docker build -t $(PROJECT_NAME)-apache:$(TAG) -f Dockerfile_apache .

push:
	docker tag $(PROJECT_NAME)-php\:$(TAG) $(DOCKER_ACCOUNT)/$(PROJECT_NAME)-php\:$(TAG)
	docker push $(DOCKER_ACCOUNT)/$(PROJECT_NAME)-php\:$(TAG)
	docker tag $(PROJECT_NAME)-apache\:$(TAG) $(DOCKER_ACCOUNT)/$(PROJECT_NAME)-apache\:$(TAG)
	docker push $(DOCKER_ACCOUNT)/$(PROJECT_NAME)-apache\:$(TAG)

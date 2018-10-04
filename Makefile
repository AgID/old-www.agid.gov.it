include .env

.PHONY: up down stop prune ps shell drush logs solr-core

default: up

DRUPAL_ROOT ?= /var/www/html/web

dir=$(shell pwd)

up:
	@echo "Starting up containers for $(PROJECT_NAME)..."
	docker-compose pull
	docker-compose up -d --remove-orphans

down: stop

stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	@docker-compose stop

prune:
	@echo "Removing containers for $(PROJECT_NAME)..."
	@docker-compose down -v

ps:
	@docker ps --filter name='$(PROJECT_NAME)*'

shell:
	docker exec -ti -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") bash

drush:
	docker exec $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") drush -r $(DRUPAL_ROOT) $(filter-out $@,$(MAKECMDGOALS))

drupal:
	docker exec $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") drupal --root=$(DRUPAL_ROOT) $(filter-out $@,$(MAKECMDGOALS))

sqlc:
	docker exec -ti $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") drush  -r $(DRUPAL_ROOT) sql-cli --extra=-A

cs:
	docker exec $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") bin/phpcs --standard=Drupal --extensions='php,module,inc,install,test,profile,theme,info' /var/www/html/web/modules/custom /var/www/html/web/themes/custom"

cbf:
	docker exec $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") bin/phpcbf --standard=Drupal --extensions='php,module,inc,install,test,profile,theme,info' /var/www/html/web/modules/custom /var/www/html/web/themes/custom"

logs:
	@docker-compose logs -f $(filter-out $@,$(MAKECMDGOALS))

alias:
	@source .aliases

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

# https://stackoverflow.com/a/6273809/1826109
%:
	@:
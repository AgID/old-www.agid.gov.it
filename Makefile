include .env

.PHONY: up down stop prune ps shell drush logs

default: up

DRUPAL_ROOT ?= /var/www/html/web

up:
	@echo "Starting up containers for $(PROJECT_NAME)..."
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

shell-node:
	docker exec --user node -ti -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(shell docker ps --filter name='$(PROJECT_NAME)_node' --format "{{ .ID }}") bash

solr-core
    docker-compose exec solr make create core="default" host="localhost" config_set="my_conf" -f /usr/local/bin/actions.mk

include .env

.PHONY: up down stop prune up-stage down-stage stop-stage prune-stage up-prod down-prod stop-prod prune-prod ps shell exec drush drupal sqlc cs cbf logs solr-core build push help

default: help

DRUPAL_ROOT ?= /var/www/html/web

dir=$(shell pwd)

up:
	@echo "Starting up containers for for $(PROJECT_NAME)..."
	docker-compose -f docker-compose.yml -f docker-compose.override.$(COMPOSE_OVERRIDE).yml up -d --remove-orphans

down: stop

stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	@docker-compose -f docker-compose.yml -f docker-compose.override.$(COMPOSE_OVERRIDE).yml stop

prune:
	@echo "Removing containers for $(PROJECT_NAME)..."
	@docker-compose -f docker-compose.yml -f docker-compose.override.$(COMPOSE_OVERRIDE).yml down -v

ps: ## List container for project
	@docker ps --filter name='$(PROJECT_NAME)*'

shell: ## Open the shell in PHP container
	docker exec -ti -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") bash

exec: ## Run a command in PHP container
	docker exec $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") $(filter-out $@,$(MAKECMDGOALS))

drush: ## Run a command drush in PHP container
	docker exec $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") bin/drush -r $(DRUPAL_ROOT) $(filter-out $@,$(MAKECMDGOALS))

drupal: ## Run a command drupalConsole in PHP container
	docker exec $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") bin/drupal --root=$(DRUPAL_ROOT) $(filter-out $@,$(MAKECMDGOALS))

sqlc: ## Open a SQL command-line interface using Drupal's credentials
	docker exec -ti $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") bin/drush  -r $(DRUPAL_ROOT) sql-cli --extra=-A

cs: ## Run the PHP Code sniffer
	docker exec $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") bin/phpcs --standard=Drupal --extensions='php,module,inc,install,test,profile,theme,info' /var/www/html/web/modules/custom /var/www/html/web/themes/custom"

cbf: ## Run the PHP Code sniffer fixing error automatically
	docker exec $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") bin/phpcbf --standard=Drupal --extensions='php,module,inc,install,test,profile,theme,info' /var/www/html/web/modules/custom /var/www/html/web/themes/custom"

logs: ## Fetch the logs of project
	@docker-compose logs -f $(filter-out $@,$(MAKECMDGOALS))

solr-core: ## Create the core in solr for Drupal site
	@docker exec www_agid_gov_it_solr make create core="default" host="localhost" config_set="agid" -f /usr/local/bin/actions.mk

build: ## Build docker image custom
	@echo "Building containers for $(PROJECT_NAME)..."
	@docker run --rm -it -v "${dir}/docroot:/app" -w "/app" wodby/php:${PHP_TAG} composer install --no-interaction --prefer-dist
	@docker build -t $(PROJECT_NAME)-php:$(PROJECT_TAG) -f Dockerfile_php .
	@docker build -t $(PROJECT_NAME)-apache:$(PROJECT_TAG) -f Dockerfile_apache .

push: ## Push docker image in docker hub
	@echo "Pushing containers for $(PROJECT_NAME) to remote repository..."
	@docker PROJECT_TAG $(PROJECT_NAME)-php\:$(PROJECT_TAG) $(DOCKER_ACCOUNT)/$(PROJECT_NAME)-php\:$(PROJECT_TAG)
	@docker push $(DOCKER_ACCOUNT)/$(PROJECT_NAME)-php\:$(PROJECT_TAG)
	@docker PROJECT_TAG $(PROJECT_NAME)-apache\:$(PROJECT_TAG) $(DOCKER_ACCOUNT)/$(PROJECT_NAME)-apache\:$(PROJECT_TAG)
	@docker push $(DOCKER_ACCOUNT)/$(PROJECT_NAME)-apache\:$(PROJECT_TAG)

help: ## List of commands
	@eval $$(sed -r -n 's/^([a-zA-Z0-9_-]+):.*?## (.*)$$/printf "\\033[36m%-30s\\033[0m %s\\n" "\1" "\2" ;/; ta; b; :a p' $(MAKEFILE_LIST))

# https://stackoverflow.com/a/6273809/1826109
%:
	@:

USER_ID=$(shell id -u)

DC = @USER_ID=$(USER_ID) docker compose
PHP_RUN = ${DC} run --rm sio_php
PHP_EXEC = ${DC} exec sio_php
PG_EXEC = ${DC} exec sio_pg

PHONY: help
.DEFAULT_GOAL := help

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

init: down build install up success-message php ## Initialize environment

build: ## Build services.
	${DC} build $(c)

up: ## Create and start services.
	${DC} up -d $(c)

stop: ## Stop services.
	${DC} stop $(c)

start: ## Start services.
	${DC} start $(c)

down: ## Stop and remove containers and volumes.
	${DC} down -v $(c)

restart: stop start ## Restart services.

install: ## Install dependencies without running the whole application.
	${PHP_RUN} composer install

php: ## PHP container
	${PHP_EXEC} /bin/bash

pg: ## PG container
	${PG_EXEC} psql -U $${POSTGRES_USER:-sio} -d $${POSTGRES_DB:-sio}

# Database
db-create: ## Create database
	${PHP_EXEC} php bin/console doctrine:database:create --if-not-exists

migrate: ## Run migrations
	${PHP_EXEC} php bin/console doctrine:migrations:migrate --no-interaction

migration: ## Generate new migration
	${PHP_EXEC} php bin/console make:migration

# Tests
test: ## Run tests
	${PHP_EXEC} php bin/phpunit

success-message:
	@echo "You can now access the application at http://localhost:8337"
	@echo "Good luck! 🚀"
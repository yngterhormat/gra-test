SHELL := /bin/bash
COMPOSE := docker-compose
APP := $(COMPOSE) exec -T testapp

.DEFAULT_GOAL := help

COMPONENT=dp
USER_DOCKER=yngmulia
BRANCH := $(shell git rev-parse --abbrev-ref HEAD)
DATENOW = $(shell date +%Y%m%d)
DOCKER_COMPONENT = ${USER_DOCKER}/${COMPONENT}

.PHONY: help up down build logs test test-suite test-file test-filter test-db-reset db-reset db-reset-test migrate seed shell tinker run_ut docker_build

help: ## Show available targets
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-16s\033[0m %s\n", $$1, $$2}'

up: ## Build and start all containers
	$(COMPOSE) up -d

down: ## Stop containers (keep data volumes)
	$(COMPOSE) down

build: ## Rebuild images and start containers
	$(COMPOSE) up -d --build

logs: ## Follow application logs
	$(COMPOSE) logs -f testapp

test: ## Run the full test suite inside the testapp container
	$(APP) ./vendor/bin/phpunit

test-suite: ## Run a test suite: make test-suite SUITE=Integration
	$(APP) ./vendor/bin/phpunit --testsuite=$(SUITE)

test-file: ## Run one test file: make test-file FILE=tests/Integration/UserAccountTest.php
	$(APP) ./vendor/bin/phpunit $(FILE)

test-filter: ## Run tests matching a name: make test-filter FILTER=test_create_user_account
	$(APP) ./vendor/bin/phpunit --filter $(FILTER)

test-db-reset: ## Rebuild + seed the test database (db_test)
	$(APP) env DB_DATABASE=${DB_DATABASE} php artisan migrate:fresh --seed --force

db-reset: ## Rebuild + seed the dev database (db)
	$(APP) php artisan migrate:fresh --seed --force

migrate: ## Run pending migrations on the dev database
	$(APP) php artisan migrate --force

seed: ## Seed the dev database
	$(APP) php artisan db:seed --force

shell: ## Open a shell inside the testapp container
	$(COMPOSE) exec testapp sh

tinker: ## Open a tinker session inside the testapp container
	$(COMPOSE) exec testapp php artisan tinker

run_ut: ## Full unit test run: down + up + test-db-reset + test
	@$(MAKE) --no-print-directory down
	@$(MAKE) --no-print-directory up
	@$(MAKE) --no-print-directory test-db-reset
	@$(MAKE) --no-print-directory test

docker_build: down
	docker build --build-arg BUILD_DEVELOPMENT=$(bd) -t ${DOCKER_COMPONENT}:$(t).${BRANCH}-${DATENOW} . --no-cache
	docker tag ${DOCKER_COMPONENT}:$(t).${BRANCH}-${DATENOW} ${DOCKER_COMPONENT}:$(t).${BRANCH}-${DATENOW}

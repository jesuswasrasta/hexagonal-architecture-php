# ABOUTME: Makefile for common Docker development tasks
.PHONY: help build up down restart install test test-unit test-functional phpstan console shell logs clean

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build Docker containers
	docker compose build

up: ## Start Docker containers
	docker compose up -d

down: ## Stop Docker containers
	docker compose down

restart: down up ## Restart Docker containers

install: ## Install Composer dependencies
	docker compose exec app composer install

test: ## Run all PHPUnit tests
	docker compose exec app php bin/phpunit

test-unit: ## Run unit tests only
	docker compose exec app php bin/phpunit --testsuite=unit

test-functional: ## Run functional tests only
	docker compose exec app php bin/phpunit --testsuite=functional

phpstan: ## Run PHPStan static analysis
	docker compose exec app composer run phpstan

console: ## List available console commands
	docker compose exec app php bin/console list

shell: ## Open a shell in the PHP container
	docker compose exec app bash

logs: ## Show container logs
	docker compose logs -f app

clean: ## Remove containers, volumes, and vendor directory
	docker compose down -v
	rm -rf vendor/

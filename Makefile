.PHONY: help install generate test shell clean up down migrate prepare

# Default target
.DEFAULT_GOAL := help

## help: Display this help message
help:
	@echo "Dollie SDK - Docker Commands"
	@echo ""
	@echo "Usage: make [target]"
	@echo ""
	@echo "Available targets:"
	@awk 'BEGIN {FS = ":.*##"; printf ""} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

## install: Install Composer dependencies
install:
	docker compose run --rm composer install

## update: Update Composer dependencies
update:
	docker compose run --rm composer update

## generate: Generate the manifest
generate:
	docker compose run --rm php php scripts/generate-manifest.php

## test: Run PHPUnit tests
test:
	docker compose run --rm php vendor/bin/phpunit

## validate: Validate composer.json
validate:
	docker compose run --rm composer validate --strict

## shell: Open a shell in the PHP container
shell:
	docker compose run --rm php bash

## php: Run a PHP command (usage: make php CMD="php -v")
php:
	docker compose run --rm php $(CMD)

## composer: Run a Composer command (usage: make composer CMD="show")
composer:
	docker compose run --rm composer $(CMD)

## clean: Remove vendor directory and generated files
clean:
	rm -rf vendor/
	rm -rf dist/
	rm -rf .phpunit.cache/
	rm -f composer.lock

## up: Start containers in background
up:
	docker compose up -d

## down: Stop and remove containers
down:
	docker compose down

## build: Build Docker images
build:
	docker compose build

## logs: Show container logs
logs:
	docker compose logs -f

## migrate: Migrate integrations from platform to SDK
migrate:
	docker compose run --rm php php scripts/migrate-integrations.php

## prepare: Add attributes to migrated integrations
prepare:
	docker compose run --rm php php scripts/prepare-integrations.php

## setup: Initial setup (install dependencies and generate manifest)
setup: install generate
	@echo "Setup complete! Manifest generated."

## ci: Run CI checks (validate, install, generate, test)
ci: validate install generate test
	@echo "CI checks passed!"

help:
	@echo "Docker Compose Help"
	@echo "-----------------------"
	@echo ""
	@echo "See contents of Makefile for more targets."

composer:
	composer install --ignore-platform-reqs --no-scripts

up:
	@docker compose up -d

stop:
	@docker compose stop

php: start-php
	@docker compose exec app bash

start-php:
	@docker compose up -d app

start-cache:
	@docker compose up -d cache

test: start-php
	@docker compose exec app ./vendor/bin/phpunit tests
migrate: start-php
	@docker compose exec app php artisan migrate

migrate-rollback: start-php
	@docker compose exec app php artisan migrate:rollback

migrate-refresh: start-php
	@docker compose exec app php artisan migrate:refresh

cache redis: start-cache
	@docker compose exec cache redis-cli

.PHONY: up stop start-php cache start-cache test php composer migrate migrate-rollback migrate-refresh

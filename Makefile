PROJECT_NAME := 'Youtube Blog'
UNAME_S := $(shell uname -s)
ifeq ($(UNAME_S),Linux)
	DOCKER_COMPOSE_FILE := $(shell pwd)/docker-compose.yml
endif
ifeq ($(UNAME_S),Darwin)
	DOCKER_COMPOSE_FILE := $(shell pwd)/docker-compose-mac.yml
endif

ACTIVE_BRANCH := $(shell (git branch |grep '*'|tr -d '*'|tr -d ' '))
DATABASE_SERVICE := 'postgres'
DATABASE_NAME := 'youtube_blog'
WEB_SERVICE := 'nginx'
PHP_SERVICE := 'php'

start:
	docker-compose -f $(DOCKER_COMPOSE_FILE) up -d
stop: 
	docker-compose -f $(DOCKER_COMPOSE_FILE) stop

rebuild:
	docker-compose -f $(DOCKER_COMPOSE_FILE) build $(PHP_SERVICE)

install:
	docker-compose -f $(DOCKER_COMPOSE_FILE) run --rm $(PHP_SERVICE) composer install -d /app

shell:
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec $(PHP_SERVICE) bash

db:
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec $(DATABASE_SERVICE) bash
db-remove:
	docker-compose -f $(DOCKER_COMPOSE_FILE) run --rm $(PHP_SERVICE) /app/bin/console doctrine:database:drop --force

db-create:
	docker-compose -f $(DOCKER_COMPOSE_FILE) run --rm $(PHP_SERVICE) /app/bin/console doctrine:database:create

db-reset: db-remove db-create migrate load-fixtures 

migrate:
	docker-compose -f $(DOCKER_COMPOSE_FILE) run --rm $(PHP_SERVICE) /app/bin/console doctrine:migration:migrate -n

load-fixtures:
	docker-compose -f $(DOCKER_COMPOSE_FILE) run --rm $(PHP_SERVICE) /app/bin/console doctrine:fixtures:load -n

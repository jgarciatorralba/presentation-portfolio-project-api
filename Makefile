.PHONY: build up down

ENV ?= dev

ifeq ($(ENV),dev)
COMPOSE_FILE := docker-compose.dev.yml
else ifeq ($(ENV),prod)
COMPOSE_FILE := docker-compose.yml
else
$(error ENV must be either 'dev' or 'prod')
endif

COMPOSE := cd .docker && docker compose -f $(COMPOSE_FILE)

build:
	$(COMPOSE) build

up:
	$(COMPOSE) up -d --build

down:
	$(COMPOSE) down

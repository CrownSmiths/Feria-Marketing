# Feria-Marketing — development workflow
# NOTE: These targets are for local development only.
# In production the site content is deployed to /var/www/ separately.

COMPOSE := docker compose

.PHONY: up
up: ## Start all services in detached mode
	$(COMPOSE) up

.PHONY: down
down: ## Stop and remove containers
	$(COMPOSE) down

.PHONY: logs
logs: ## Tail logs from all services
	$(COMPOSE) logs -f

.PHONY: ps
ps: ## Show running services
	$(COMPOSE) ps
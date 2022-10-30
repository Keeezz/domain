isDocker := $(shell docker info > /dev/null 2>&1 && echo 1)
user := $(shell id -u)
group := $(shell id -g)

ifeq ($(isDocker), 1)
	dc := USER_ID=$(user) GROUP_ID=$(group) docker-compose
	de := docker-compose exec
	sy := $(de) symfony console
else
	sy := php bin/console
	node :=
	php :=
endif

DISABLE_XDEBUG=XDEBUG_MODE=off

.DEFAULT_GOAL := help
.PHONY: help
help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: dev
dev: ## Start containers
	$(dc) build && $(dc) up

.PHONY: test-watch
test-watch: ## Start test watcher
	$(de) php phpunit-watcher watch

.PHONY: run-fish
run-fish: ## Start fish
	$(de) php fish

.PHONY: phpstan
phpstan:
	$(DISABLE_XDEBUG) php vendor/bin/phpstan analyse -c phpstan.neon
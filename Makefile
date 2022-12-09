.PHONY: phpstan tests fix analyse

DISABLE_XDEBUG=XDEBUG_MODE=off

install:
	composer install

phpstan:
	$(DISABLE_XDEBUG) php vendor/bin/phpstan analyse -c phpstan.neon

tests:
	php vendor/bin/phpunit-watcher watch

fix:
	$(DISABLE_XDEBUG) php vendor/bin/php-cs-fixer fix

qa: fix analyse

tests-wc:
	$(DISABLE_XDEBUG) php vendor/bin/phpunit --no-coverage
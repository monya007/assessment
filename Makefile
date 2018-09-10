include docker.mk

.PHONY: test

DRUPAL_VER ?= 8
PHP_VER ?= 7.2

# Defines colors for echo, eg: @echo "${GREEN}Hello World${COLOR_END}". More colors: https://stackoverflow.com/a/43670199/3090657
YELLOW=\033[0;33m
RED=\033[0;31m
GREEN=\033[0;32m
COLOR_END=\033[0;37m

test:
	cd ./tests/$(DRUPAL_VER) && PHP_VER=$(PHP_VER) ./run.sh

install:
	@echo "${YELLOW}Installing React.js application...${COLOR_END}"
	docker-compose run node yarn install
	docker-compose down  --remove-orphans
	docker-compose up -d  --remove-orphans
	@echo "${YELLOW}Installing Drupal...${COLOR_END}"
	docker-compose exec php composer install
	docker-compose exec php drush si config_installer -y --account-name=admin --account-pass=admin
	@echo "${GREEN}The platform is ready to use!${COLOR_END}"

run-tests:
	@echo "${YELLOW}Running tests...${COLOR_END}"
	docker-compose exec php sh -c "cd /var/www/html/web/core && phpunit --group=math_field"
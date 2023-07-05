export PROJECT_NAME := user-component
export CURRENT_PATH := $(shell pwd)
export USER_CONTAINER := user

DOCKER_COMPOSE=docker-compose -p ${PROJECT_NAME} -f ${CURRENT_PATH}/ops/docker/docker-compose.yml -f ${CURRENT_PATH}/ops/docker/docker-compose.dev.yml

restart: stop start

start: docker-build docker-up logs

stop:
	@${DOCKER_COMPOSE} down --remove-orphans

docker-build:
	@${DOCKER_COMPOSE} build

docker-up:
	@${DOCKER_COMPOSE} up -d

logs:
	@${DOCKER_COMPOSE} logs -f


# Database
database-create:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} php bin/console doctrine:database:create --if-not-exists

generate-migrations:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} php bin/console doctrine:migrations:diff

execute-migrations:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} php bin/console --no-interaction doctrine:migrations:migrate

# Composer

composer-install:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} composer install

composer-require:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} composer require ${PACKAGE}

composer-remove:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} composer remove ${PACKAGE}

shell:
	@${DOCKER_COMPOSE_STAGE} exec ${USER_CONTAINER} bash

# Tests

tests-functional:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} php bin/phpunit --testsuite Functional

tests-unit:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} php bin/phpunit --testsuite Unit

test-functional:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} php bin/phpunit --testsuite Functional --filter ${TEST}

test-unit:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} php bin/phpunit --testsuite Unit --filter ${TEST}

tests-all:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} php bin/phpunit

# Code style

cs-fix-dry:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} php vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix:
	@${DOCKER_COMPOSE} exec ${USER_CONTAINER} php vendor/bin/php-cs-fixer fix

# Open

open-api:
	@open http://localhost:8080/api

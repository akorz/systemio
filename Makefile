.ONESHELL:
SHELL := /bin/bash

DIR:=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))

USER_ID=$(shell id -u)
GROUP_ID=$(shell id -g)

include ${DIR}/.env
-include ${DIR}/.env.local
export

build:
	docker compose -p ${DOCKER_CONTAINER_PREFIX}_${PROJECT_NAME} -f ${DIR}/docker-compose.yml build

composer:
	docker exec -it ${DOCKER_CONTAINER_PREFIX}_${PROJECT_NAME}_app composer install

fixtures:
	docker exec -it ${DOCKER_CONTAINER_PREFIX}_${PROJECT_NAME}_app bin/console doctrine:migrations:migrate
	docker exec -it ${DOCKER_CONTAINER_PREFIX}_${PROJECT_NAME}_app bin/console doctrine:fixtures:load

start:
	docker compose -p ${DOCKER_CONTAINER_PREFIX}_${PROJECT_NAME} -f ${DIR}/docker-compose.yml up -d

stop:
	docker compose -p ${DOCKER_CONTAINER_PREFIX}_${PROJECT_NAME} -f ${DIR}/docker-compose.yml down

restart: stop start

test-start:
	docker compose -p ${DOCKER_CONTAINER_PREFIX}_${PROJECT_NAME}_test -f ${DIR}/docker-compose.test.yml up -d

test-stop:
	docker compose -p ${DOCKER_CONTAINER_PREFIX}_${PROJECT_NAME}_test -f ${DIR}/docker-compose.test.yml down

test-test:
	docker exec -i \
		-e WAIT_HOSTS=mysql:3306 \
        -e WAIT_BEFORE_HOSTS=5 \
        -e WAIT_AFTER_HOSTS=1 \
        -e WAIT_HOSTS_TIMEOUT=300 \
        -e WAIT_SLEEP_INTERVAL=30 \
        -e WAIT_HOST_CONNECT_TIMEOUT=30 \
        ${DOCKER_CONTAINER_PREFIX}_${PROJECT_NAME}_test_app \
        /usr/local/bin/wait

	docker exec -it ${DOCKER_CONTAINER_PREFIX}_${PROJECT_NAME}_test_app bin/phpunit

test: test-start test-test test-stop
#!/usr/bin/env bash

source ../../dev 2> /dev/null;

function docker-build() {
    COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose ${composeFiles} -p buzzingpixel build;

    return 0;
}

function docker-up() {
    docker network create proxy >/dev/null 2>&1;

    chmod -R 0777 storage;

    COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose ${composeFiles} -p buzzingpixel up -d;

    docker exec -it -w /opt/project buzzingpixel-php bash -c "chmod -R 0777 /opt/project/storage";

    return 0;
}

function docker-down() {
    docker-compose ${composeFiles} -p buzzingpixel down;

    return 0;
}

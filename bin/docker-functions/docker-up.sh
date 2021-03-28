#!/usr/bin/env bash

function docker-up() {
    docker network create proxy >/dev/null 2>&1;

    chmod -R 0777 storage;

    COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose ${composeFiles} -p buzzingpixel up -d;

    docker exec -it -w /opt/project buzzingpixel-php bash -c "chmod -R 0777 /opt/project/storage";

    return 0;
}

#!/usr/bin/env bash

source ../../dev 2> /dev/null;

function up() {
    docker network create proxy >/dev/null 2>&1;

    chmod -R 0777 storage;

    COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose ${composeFiles} -p buzzingpixel up -d;

    docker exec -it -w /opt/project buzzingpixel-php bash -c "chmod -R 0777 /opt/project/storage";

    return 0;
}

function down() {
    docker kill buzzingpixel-bg-sync-node-modules >/dev/null 2>&1;
    docker kill buzzingpixel-bg-sync-storage >/dev/null 2>&1;
    docker kill buzzingpixel-bg-sync-vendor >/dev/null 2>&1;
    docker-compose ${composeFiles} -p buzzingpixel down;

    return 0;
}

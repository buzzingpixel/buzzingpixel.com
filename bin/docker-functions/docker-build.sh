#!/usr/bin/env bash

function docker-build() {
    COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose ${composeFiles} -p buzzingpixel build;

    return 0;
}

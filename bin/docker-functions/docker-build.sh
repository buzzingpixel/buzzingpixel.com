#!/usr/bin/env bash

function docker-build() {
    # Ensure local env files exist
    # docker-ensure-local-env-files;

    chmod +x docker/bin/*;

    docker/bin/build.sh;

    # COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose ${composeFiles} -p buzzingpixel build;

    return 0;
}

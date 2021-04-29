#!/usr/bin/env bash

function docker-build-help() {
    printf "(Build the Docker images for this project)";
}

function docker-build() {
    chmod +x docker/bin/*;

    docker/bin/build.sh;

    return 0;
}

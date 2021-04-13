#!/usr/bin/env bash

function docker-down-help {
    printf "(Spins down the Docker environment)";
}

function docker-down() {
    docker-compose ${composeFiles} -p buzzingpixel down;

    return 0;
}

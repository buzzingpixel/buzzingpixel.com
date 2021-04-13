#!/usr/bin/env bash

function docker-refresh-help() {
    printf "(Spins down the Docker environment then bring it back online)";
}

function docker-refresh() {
    docker-down;
    docker-up;

    return 0;
}

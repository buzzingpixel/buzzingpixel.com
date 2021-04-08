#!/usr/bin/env bash

function container-node() {
    docker run -it \
        -v ${PWD}:/app \
        -w /app \
        ${nodeDockerImage} bash -c "${allArgsExceptFirst}";
}

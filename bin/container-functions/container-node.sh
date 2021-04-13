#!/usr/bin/env bash

function container-node-help() {
    printf "[some_command] (Execute command in \`node\` image. Empty argument starts a bash session)";
}

function container-node() {
    docker run -it \
        -v ${PWD}:/app \
        -w /app \
        ${nodeDockerImage} bash -c "${allArgsExceptFirst}";
}

#!/usr/bin/env bash

function container-node() {
    docker run -it \
        -v ${PWD}:/app \
        -v buzzingpixel_yarn-cache-volume:/usr/local/share/.cache/yarn \
        -w /app \
        --network=proxy \
        ${nodeDockerImage} bash -c "${allArgsExceptFirst}";
}

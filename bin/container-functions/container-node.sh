#!/usr/bin/env bash

function container-node-help() {
    printf "[some_command] (Execute command in \`node\` image. Empty argument starts a bash session)";
}

function container-node() {
    if [[ -z "${allArgsExceptFirst}" ]]; then
        printf "${Yellow}Remember to 'exit' when you're done.${Reset}\n";
        docker run -it \
            -v ${PWD}:/app \
            -w /app \
            ${nodeDockerImage} bash;
    else
        docker run -it \
            -v ${PWD}:/app \
            -w /app \
            ${nodeDockerImage} bash -c "${allArgsExceptFirst}";
    fi

    return 0;
}

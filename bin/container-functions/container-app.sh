#!/usr/bin/env bash

function container-app() {
    printf "${Yellow}You're working inside the 'app' container of this project.${Reset}\n";

    if [[ -z "${allArgsExceptFirst}" ]]; then
        printf "${Yellow}Remember to 'exit' when you're done.${Reset}\n";
        docker exec -it -w /opt/project buzzingpixel-app bash;
    else
        docker exec -it -w /opt/project buzzingpixel-app bash -c "${allArgsExceptFirst}";
    fi

    return 0;
}

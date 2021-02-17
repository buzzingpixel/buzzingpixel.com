#!/usr/bin/env bash

source ../../dev 2> /dev/null;

function php() {
    printf "${Yellow}You're working inside the 'web' container of this project.${Reset}\n";

    if [[ -z "${allArgsExceptFirst}" ]]; then
        printf "${Yellow}Remember to 'exit' when you're done.${Reset}\n";
        docker exec -it -w /opt/project buzzingpixel-nginx bash;
    else
        docker exec -it -w /opt/project buzzingpixel-nginx bash -c "${allArgsExceptFirst}";
    fi

    return 0;
}

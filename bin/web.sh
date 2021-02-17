#!/usr/bin/env bash

source ../../dev 2> /dev/null;

function web() {
    printf "${Yellow}You're working inside the 'web' container of this project.${Reset}\n";

    if [[ -z "${allArgsExceptFirst}" ]]; then
        printf "${Yellow}Remember to 'exit' when you're done.${Reset}\n";
        docker exec -it -w /opt/project buzzingpixel-web bash;
    else
        docker exec -it -w /opt/project buzzingpixel-web bash -c "${allArgsExceptFirst}";
    fi

    return 0;
}

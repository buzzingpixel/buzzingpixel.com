#!/usr/bin/env bash

source ../../dev 2> /dev/null;

function php() {
    printf "${Yellow}You're working inside the 'php-fpm' container of this project.${Reset}\n";

    if [[ -z "${allArgsExceptFirst}" ]]; then
        printf "${Yellow}Remember to 'exit' when you're done.${Reset}\n";
        docker exec -it -w /opt/project buzzingpixel-php bash;
    else
        docker exec -it -w /opt/project buzzingpixel-php bash -c "${allArgsExceptFirst}";
    fi

    return 0;
}

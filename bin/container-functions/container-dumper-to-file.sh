#!/usr/bin/env bash

function container-dumper-to-file-help() {
    printf "[some_command] (Run server dumper html to storage/dump.html)";
}

function container-dumper-to-file() {
    if [ -t 0 ]; then
        interactiveArgs='-it';
    else
        interactiveArgs='';
    fi

    docker exec ${interactiveArgs} -w /opt/project buzzingpixel-app bash -c "XDEBUG_MODE=off php cli server:dump --format=html > /opt/project/storage/dump.html";

    return 0;
}

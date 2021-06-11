#!/usr/bin/env bash

function container-dumper-to-file-help() {
    printf "[some_command] (Run server dumper html to storage/dump.html)";
}

function container-dumper-to-file() {
    docker exec -it -w /opt/project buzzingpixel-app bash -c "XDEBUG_MODE=off php cli server:dump --format=html > /opt/project/storage/dump.html";

    return 0;
}

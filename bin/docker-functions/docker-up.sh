#!/usr/bin/env bash

function docker-up-help() {
    printf "(Brings Docker environment online)";
}

function docker-up() {
    chmod -R 0777 storage;

    docker compose ${composeFiles} -p buzzingpixel up -d;

    docker exec -it buzzingpixel-app bash -c "chmod -R 0777 /opt/project/storage";

    return 0;

}

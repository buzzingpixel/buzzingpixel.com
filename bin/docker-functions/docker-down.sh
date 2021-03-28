#!/usr/bin/env bash

function docker-down() {
    docker-compose ${composeFiles} -p buzzingpixel down;

    return 0;
}

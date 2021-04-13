#!/usr/bin/env bash

function docker-refresh() {
    docker-down;
    docker-up;

    return 0;
}

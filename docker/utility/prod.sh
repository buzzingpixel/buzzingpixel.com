#!/usr/bin/env bash

mkdir -p /log-volume/nginx;

touch /log-volume/nginx/error.log;

chmod -R 0777 /log-volume;

while true; do
    chmod -R 0777 /storage-volume;
    sleep 120;
done

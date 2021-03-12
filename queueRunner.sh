#!/usr/bin/env bash

while true; do
    php /opt/project/cli queue:run --quiet;
    sleep 1;
done

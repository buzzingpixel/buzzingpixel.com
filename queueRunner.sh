#!/usr/bin/env bash

while true; do
    /usr/bin/docker exec -w /opt/project buzzingpixel-app bash -c "XDEBUG_MODE=off php cli queue:run --quiet";
    sleep 1;
done

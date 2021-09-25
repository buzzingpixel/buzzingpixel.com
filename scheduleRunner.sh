#!/usr/bin/env bash

if [[ -f "/root/buzzingpixel.com/disableSchedule" ]]; then
    /usr/bin/docker exec -w /opt/project buzzingpixel-app bash -c "XDEBUG_MODE=off php cli schedule:run --quiet";
fi

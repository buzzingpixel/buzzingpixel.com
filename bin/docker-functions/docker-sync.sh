#!/usr/bin/env bash

source ../../dev 2> /dev/null;

function docker-sync-help() {
    printf "(Syncs production database and content to local environment)";
}

function docker-sync() {
    chmod +x docker/scripts/dev/ensure-ssh-keys-working.sh;
    chmod +x docker/scripts/dev/sync-to-local-01-ssh-db-schema.sh;
    chmod +x docker/scripts/dev/sync-to-local-02-ssh-db-data.sh;
    chmod +x docker/scripts/dev/sync-to-local-03-db-restore-schema.sh;
    chmod +x docker/scripts/dev/sync-to-local-04-db-restore-data.sh;
    chmod +x docker/scripts/dev/sync-to-local-05-rsync.sh;

    docker compose ${composeFiles} -p buzzingpixel down;
    docker volume rm buzzingpixel_db-volume;
    docker compose ${composeFiles} -p buzzingpixel up -d;
    docker exec ${interactiveArgs} -w /opt/project buzzingpixel-app bash -c "XDEBUG_MODE=off php cli database:setup";

    docker compose -f docker-compose.sync.to.local.yml -p buzzingpixel-ssh up -d;
    docker exec buzzingpixel-ssh bash -c "/opt/project/docker/scripts/dev/sync-to-local-01-ssh-db-schema.sh;";
    docker exec buzzingpixel-ssh bash -c "/opt/project/docker/scripts/dev/sync-to-local-02-ssh-db-data.sh;";
    docker exec buzzingpixel-db bash -c "/opt/project/docker/scripts/dev/sync-to-local-03-db-restore-schema.sh;";
    docker exec buzzingpixel-db bash -c "/opt/project/docker/scripts/dev/sync-to-local-04-db-restore-data.sh;";
    docker exec buzzingpixel-ssh bash -c "/opt/project/docker/scripts/dev/sync-to-local-05-rsync.sh;";
    docker compose -f docker-compose.sync.to.local.yml -p buzzingpixel-ssh down;
    return 0;
}

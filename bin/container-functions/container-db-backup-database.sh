#!/usr/bin/env bash

function container-db-backup-database-help() {
    printf "(Backs up the database to a file)";
}

function container-db-backup-database() {
    DATE=$(date +"%Y-%m-%d__%H-%M-%S");

    mkdir -p docker/localStorage/dbBackups;

    docker exec -it buzzingpixel-db bash -c 'pg_dump --dbname=postgresql://${DB_USER}:${DB_PASSWORD}@127.0.0.1:5432/${DB_DATABASE} -w -Fc > /dump.psql';

    docker cp buzzingpixel-db:/dump.psql docker/localStorage/dbBackups/${DATE}.psql;

    docker exec -it buzzingpixel-db bash -c "rm /dump.psql";

    return 0;
}

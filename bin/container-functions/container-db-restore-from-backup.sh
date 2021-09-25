#!/usr/bin/env bash

function container-db-restore-from-backup-help() {
    printf "[backup-name.psgl] (Restores database from psql backup file)";
}

function container-db-restore-from-backup() {
    if [ -t 0 ]; then
        interactiveArgs='-it';
    else
        interactiveArgs='';
    fi

    if [[ -z "${secondArg}" ]]; then
        printf "${Red}You must provide the backup name as the second argument${Reset}\n";

        exit;
    fi

    if [[ ! -f "docker/localStorage/dbBackups/${secondArg}" ]]; then
        printf "${Red}The specified file does not exist in docker/localStorage/dbBackups${Reset}\n";

        exit;
    fi

    docker cp docker/localStorage/dbBackups/${secondArg} buzzingpixel-db:/dump.psql;

    docker exec ${interactiveArgs} buzzingpixel-db bash -c 'PGPASSWORD="${DB_PASSWORD}"; pg_restore --clean -U "${DB_USER}" -d "${DB_DATABASE}" -v < "/dump.psql"';

    docker exec ${interactiveArgs} buzzingpixel-db bash -c "rm /dump.psql";

    return 0;
}

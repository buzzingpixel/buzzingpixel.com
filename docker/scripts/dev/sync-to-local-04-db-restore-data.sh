#!/usr/bin/env bash

SQL_FILE_NAME_DATA="buzzingpixel.psql";

PGPASSWORD="${DB_PASSWORD}";

pg_restore --data-only --disable-triggers --verbose --username="${DB_USER}" --dbname="${DB_DATABASE}" "/opt/project/docker/localStorage/${SQL_FILE_NAME_DATA}";

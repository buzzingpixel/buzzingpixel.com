#!/usr/bin/env bash

SQL_FILE_NAME_SCHEMA="buzzingpixel_schema.psql";

PGPASSWORD="${DB_PASSWORD}";

pg_restore --clean --schema-only --verbose --username="${DB_USER}" --dbname="${DB_DATABASE}" "/opt/project/docker/localStorage/${SQL_FILE_NAME_SCHEMA}";

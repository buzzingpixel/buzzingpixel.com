#!/usr/bin/env bash

SERVER_USER="root";
SERVER_ADDRESS="206.81.13.32";
SQL_FILE_NAME_SCHEMA="buzzingpixel_schema.psql";
SQL_FILE_NAME_DATA="buzzingpixel.psql";
REMOTE_PROJECT_PATH="/root/buzzingpixel.com";
REMOTE_SQL_PATH_SCHEMA="${REMOTE_PROJECT_PATH}/${SQL_FILE_NAME_SCHEMA}";
REMOTE_SQL_PATH_DATA="${REMOTE_PROJECT_PATH}/${SQL_FILE_NAME_DATA}";
DB_NAME="buzzingpixel";
DB_USER="buzzingpixel";
DB_CONTAINER_NAME="buzzingpixel-db";

source /opt/project/docker/scripts/dev/ensure-ssh-keys-working.sh;

mkdir -p /opt/project/docker/localStorage;

[[ -e /opt/project/docker/localStorage/${SQL_FILE_NAME_DATA} ]] && rm /opt/project/docker/localStorage/${SQL_FILE_NAME_DATA};

# Dump the database schema on remote
ssh -i ~/.ssh/id_rsa -o StrictHostKeyChecking=no -T ${SERVER_USER}@${SERVER_ADDRESS} << HERE
    # Make sure dump file does not exist on host
    [ -e ${REMOTE_SQL_PATH_SCHEMA} ] && rm ${REMOTE_SQL_PATH_SCHEMA};

    # Make sure dump file does not exist in container
    docker exec --workdir /tmp ${DB_CONTAINER_NAME} bash -c '[ -e ${REMOTE_SQL_PATH_SCHEMA} ] && rm ${REMOTE_SQL_PATH_SCHEMA}';

    # Dump database schema in Docker container
    docker exec --workdir /tmp ${DB_CONTAINER_NAME} bash -c 'pg_dump --schema-only --format=custom --dbname=postgresql://${DB_USER}:${PROD_DB_PASSWORD}@127.0.0.1:5432/${DB_NAME} > ${SQL_FILE_NAME_SCHEMA}';

    # Copy dump out of container
    docker cp ${DB_CONTAINER_NAME}:/tmp/${SQL_FILE_NAME_SCHEMA} ${REMOTE_SQL_PATH_SCHEMA};

    # Delete the dump from the container
    docker exec --workdir /tmp ${DB_CONTAINER_NAME} bash -c '[ -e ${SQL_FILE_NAME_SCHEMA} ] && rm ${SQL_FILE_NAME_SCHEMA}';
HERE

sleep 5;

# Download database schema file
[ -e "/opt/project/docker/localStorage/${SQL_FILE_NAME_SCHEMA}" ] && rm "/opt/project/docker/localStorage/${SQL_FILE_NAME_SCHEMA}";
scp -i ~/.ssh/id_rsa -o StrictHostKeyChecking=no ${SERVER_USER}@${SERVER_ADDRESS}:${REMOTE_SQL_PATH_SCHEMA} /opt/project/docker/localStorage/${SQL_FILE_NAME_SCHEMA};

sleep 5;

# Delete database pull on remote
ssh -i ~/.ssh/id_rsa -o StrictHostKeyChecking=no -T ${SERVER_USER}@${SERVER_ADDRESS} rm ${REMOTE_SQL_PATH_SCHEMA};

sleep 5;

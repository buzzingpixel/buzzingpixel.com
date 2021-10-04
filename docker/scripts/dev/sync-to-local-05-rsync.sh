#!/usr/bin/env bash

SERVER_USER="root";
SERVER_ADDRESS="206.81.13.32";

source /opt/project/docker/scripts/dev/ensure-ssh-keys-working.sh;

# Rsync diretories

mkdir -p /opt/project/storage/softwareDownloads;
rsync -e "ssh -o StrictHostKeyChecking=no" -av ${SERVER_USER}@${SERVER_ADDRESS}:/var/lib/docker/volumes/buzzingpixel_storage-volume/_data/softwareDownloads/ /opt/project/storage/softwareDownloads;

sleep 5;

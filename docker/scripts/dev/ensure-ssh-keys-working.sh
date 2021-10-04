#!/usr/bin/env bash

mkdir -p ~/.ssh;
chmod 0700 ~/.ssh;

cp /tmp/.ssh/id_rsa ~/.ssh/id_rsa;
chmod 0600 ~/.ssh/id_rsa;

cp /tmp/.ssh/id_rsa.pub ~/.ssh/id_rsa.pub;
chmod 0644 ~/.ssh/id_rsa.pub;

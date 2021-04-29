#!/usr/bin/env bash

ARCH=${1};
S6_VERSION=${2};

if [[ ${ARCH} == "linux/arm64" ]]; then
    echo "Running: curl -L https://github.com/just-containers/s6-overlay/releases/download/${S6_VERSION}/s6-overlay-arm.tar.gz --output /tmp/s6-overlay.tar.gz";
    curl -L https://github.com/just-containers/s6-overlay/releases/download/${S6_VERSION}/s6-overlay-arm.tar.gz --output /tmp/s6-overlay.tar.gz
    echo "Running: curl -L https://github.com/just-containers/s6-overlay/releases/download/${S6_VERSION}/s6-overlay-arm.tar.gz.sig --output /tmp/s6-overlay.tar.gz.sig";
    curl -L https://github.com/just-containers/s6-overlay/releases/download/${S6_VERSION}/s6-overlay-arm.tar.gz.sig --output /tmp/s6-overlay.tar.gz.sig
else
    echo "Running: curl -L https://github.com/just-containers/s6-overlay/releases/download/${S6_VERSION}/s6-overlay
    -amd64.tar.gz --output /tmp/s6-overlay.tar.gz";
    curl -L https://github.com/just-containers/s6-overlay/releases/download/${S6_VERSION}/s6-overlay-amd64.tar.gz --output /tmp/s6-overlay.tar.gz
    echo "Running: curl -L https://github.com/just-containers/s6-overlay/releases/download/${S6_VERSION}/s6-overlay-amd64.tar.gz.sig --output /tmp/s6-overlay.tar.gz.sig";
    curl -L https://github.com/just-containers/s6-overlay/releases/download/${S6_VERSION}/s6-overlay-amd64.tar.gz.sig --output /tmp/s6-overlay.tar.gz.sig
fi

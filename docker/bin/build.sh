#!/usr/bin/env bash

#########################
# Handy Color Variables #
#########################

# Reset
Reset="\033[0m"; # Text Reset

# Regular Colors
Black="\033[0;30m"; # Black
Red="\033[0;31m"; # Red
Green="\033[0;32m"; # Green
Yellow="\033[0;33m"; # Yellow
Blue="\033[0;34m"; # Blue
Purple="\033[0;35m"; # Purple
Cyan="\033[0;36m"; # Cyan
White="\033[0;37m"; # White

# Bold
BBlack="\033[1;30m"; # Black
BRed="\033[1;31m"; # Red
BGreen="\033[1;32m"; # Green
BYellow="\033[1;33m"; # Yellow
BBlue="\033[1;34m"; # Blue
BPurple="\033[1;35m"; # Purple
BCyan="\033[1;36m"; # Cyan
BWhite="\033[1;37m"; # White

##############################
# /END Handy Color Variables #
##############################

set -e;

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";

cd ${SCRIPT_DIR};

# Run the app build
printf "${Cyan}Building ghcr.io/buzzingpixel/buzzingpixel.com-app${Reset}\n";
docker build \
    --build-arg BUILDKIT_INLINE_CACHE=1 \
    --cache-from ghcr.io/buzzingpixel/buzzingpixel.com-app \
    --file ../application/Dockerfile \
    --tag ghcr.io/buzzingpixel/buzzingpixel.com-app \
    ../../
printf "${Green}Finished ghcr.io/buzzingpixel/buzzingpixel.com-app${Reset}\n\n";

# Run the schedule-runner build
printf "${Cyan}Building ghcr.io/buzzingpixel/buzzingpixel.com-app-schedule-runner${Reset}\n";
docker build \
    --build-arg BUILDKIT_INLINE_CACHE=1 \
    --cache-from ghcr.io/buzzingpixel/buzzingpixel.com-app-schedule-runner \
    --file ../schedule-runner/Dockerfile \
    --tag ghcr.io/buzzingpixel/buzzingpixel.com-app-schedule-runner \
    ../../
printf "${Green}Finished ghcr.io/buzzingpixel/buzzingpixel.com-app-schedule-runner${Reset}\n\n";

# Run the db build
printf "${Cyan}Building ghcr.io/buzzingpixel/buzzingpixel.com-db${Reset}\n";
docker build \
    --build-arg BUILDKIT_INLINE_CACHE=1 \
    --cache-from ghcr.io/buzzingpixel/buzzingpixel.com-db \
    --file ../db/Dockerfile \
    --tag ghcr.io/buzzingpixel/buzzingpixel.com-db \
    ../../
printf "${Green}Finished ghcr.io/buzzingpixel/buzzingpixel.com-db${Reset}\n\n";

# Run the utility build
printf "${Cyan}Building ghcr.io/buzzingpixel/buzzingpixel.com-utility${Reset}\n";
docker build \
    --build-arg BUILDKIT_INLINE_CACHE=1 \
    --cache-from ghcr.io/buzzingpixel/buzzingpixel.com-utility \
    --file ../utility/Dockerfile \
    --tag ghcr.io/buzzingpixel/buzzingpixel.com-utility \
    ../../
printf "${Green}Finished ghcr.io/buzzingpixel/buzzingpixel.com-utility${Reset}\n\n";


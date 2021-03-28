#!/usr/bin/env bash

function docker-up() {
    # Create the external network
    docker network create proxy >/dev/null 2>&1;

    # Make sure requirements are met
    if [ ! $(command -v mkcert) ]; then
        printf "${Red}'mkcert' must be installed. To install mkcert, run the following: ${Reset}\n";
        printf "${Cyan}(homebrew is required)\n\n";
        printf "${Green}    brew install mkcert\n";
        printf "    brew install nss\n";
        printf "    mkcert -install${Reset}\n\n";
        printf "${Red}Halting docker-up ${Reset}\n";

        return 1;
    fi

    # Local, reusable function to make certs
    function localMkCert() {
        # Only run if our cert or key is missing
        if [ ! -f docker/web/certs/${1}.cert -o ! -f docker/web/certs/${1}.key ]; then
            printf "${Cyan}Generating new local cert for ${1} with mkcert...${Reset}\n";

            mkcert \
                -cert-file docker/web/certs/${1}.cert \
                -key-file docker/web/certs/${1}.key \
                ${1};

            printf "${Green}${1} cert created${Reset}\n";
        fi
    }

    # Ensure certificates exist with or reusable local function
    localMkCert "buzzingpixel.localtest.me";
    localMkCert "localhost";

    chmod -R 0777 storage;

    COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose ${composeFiles} -p buzzingpixel up -d;

    docker exec -it -w /opt/project buzzingpixel-php bash -c "chmod -R 0777 /opt/project/storage";

    return 0;

}

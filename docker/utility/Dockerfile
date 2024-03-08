FROM ubuntu:18.04

COPY docker/utility/prod.sh /var/www/docker/utility/prod.sh

RUN chmod +x /var/www/docker/utility/prod.sh

ENTRYPOINT ["/var/www/docker/utility/prod.sh"]

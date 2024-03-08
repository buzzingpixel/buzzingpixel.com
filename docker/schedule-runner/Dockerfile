FROM --platform=$TARGETPLATFORM ghcr.io/buzzingpixel/buzzingpixel.com-app
ARG TARGETPLATFORM
ARG TARGETARCH
ARG BUILDPLATFORM
ARG BUILDARCH

RUN apt-get update && apt-get install -y --no-install-recommends cron \
    # Cleanup
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN touch /var/log/cron.log

ENV TZ="America/Chicago"

RUN echo "America/Chicago" > /etc/timezone

RUN (crontab -l ; echo "* * * * * /var/www/scheduleRunner.sh >> /var/log/cron.log 2>&1") | crontab

ENTRYPOINT []

CMD printenv | grep -v "no_proxy" >> /etc/environment && cron && tail -f /var/log/cron.log

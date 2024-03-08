#!/usr/bin/env bash

echo "Running BuzzingPixel Schedule"

/usr/local/bin/php -f /var/www/cli schedule:run

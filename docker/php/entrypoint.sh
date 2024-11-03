#!/bin/bash

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ "$env" != "local" ]; then
    echo "Caching configuration"
    php /application/artisan config:cache
    php /application/artisan route:cache
fi

if [ "$role" = "app" ]; then
    php artisan serve --host=0.0.0.0 --port=8000

elif [ "$role" = "queue" ]; then
    echo "Running the queue"
    /usr/bin/supervisord --nodaemon -c /application/docker/php/supervisor/redis.conf

elif [ "$role" = "scheduler" ]; then
    while [ true ]
    do
      php /application/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done
else
    echo "Could not match the container role \"$role\""
    exit 1
fi

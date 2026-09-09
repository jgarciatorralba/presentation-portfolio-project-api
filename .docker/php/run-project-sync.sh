#!/bin/sh
set -eu

read_container_env() {
    key="$1"

    if [ -r /run/portfolio-api.env ]; then
        while IFS='=' read -r name value; do
            if [ "$name" = "$key" ]; then
                printf '%s\n' "$value"
                return
            fi
        done < /run/portfolio-api.env
        return
    fi

    return 1
}

export DATABASE_HOST="$(read_container_env DATABASE_HOST)"
export DATABASE_PORT="$(read_container_env DATABASE_PORT)"
export DATABASE_NAME="$(read_container_env DATABASE_NAME)"
export DATABASE_USER="$(read_container_env DATABASE_USER)"
export DATABASE_PASSWORD="$(read_container_env DATABASE_PASSWORD)"
export XDEBUG_MODE=off

cd /var/www/portfolio-api
exec php bin/console app:projects:sync
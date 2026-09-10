#!/bin/sh
set -eu

umask 077
env_file=/run/presentation-portfolio-project-api.env

for variable in DATABASE_HOST DATABASE_PORT DATABASE_NAME DATABASE_USER DATABASE_PASSWORD; do
    printf '%s=%s\n' "$variable" "$(printenv "$variable" || true)" >> "$env_file"
done

chmod 640 "$env_file"
chown www-data:www-data "$env_file"

exec "$@"
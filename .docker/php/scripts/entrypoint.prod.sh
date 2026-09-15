#!/bin/sh
set -eu

php bin/console cache:clear --no-warmup --no-interaction
php bin/console cache:warmup --no-interaction

exec "$@"

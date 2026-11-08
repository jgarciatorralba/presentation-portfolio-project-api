#!/bin/sh
set -eu

cd /var/www/presentation-portfolio-project-api

php bin/console cache:clear --no-warmup --no-interaction --env=prod
php bin/console cache:warmup --no-interaction --env=prod

exec "$@"

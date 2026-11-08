#!/bin/sh
set -eu

cd /var/www/presentation-portfolio-project-api

exec php bin/console doctrine:database:create --if-not-exists --env=prod
exec php bin/console doctrine:migrations:migrate --no-interaction --env=prod

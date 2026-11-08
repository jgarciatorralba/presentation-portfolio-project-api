#!/bin/sh
set -eu

cd /var/www/presentation-portfolio-project-api
exec php bin/console app:projects:sync --env=prod

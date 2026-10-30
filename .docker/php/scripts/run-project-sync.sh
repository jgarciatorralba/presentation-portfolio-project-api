#!/bin/sh
set -eu

export XDEBUG_MODE=off

cd /var/www/presentation-portfolio-project-api
exec php bin/console app:projects:sync

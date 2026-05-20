#!/bin/sh

set -e

echo "Starting Symfony..."

php bin/console doctrine:migrations:migrate --no-interaction --env=prod || true

php -S 0.0.0.0:10000 -t public
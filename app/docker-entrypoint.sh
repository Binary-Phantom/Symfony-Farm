#!/bin/sh
set -e

echo "🚀 Starting Symfony deploy..."

echo "📦 Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --env=prod || true

echo "🧹 Clearing cache..."
php bin/console cache:clear --env=prod || true

echo "🌐 Starting server..."
exec php -S 0.0.0.0:$PORT -t public
#!/usr/bin/env sh
set -eu

cd /var/www

if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

if [ ! -f vendor/autoload.php ]; then
  echo "[entrypoint] Installing Composer dependencies..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ -f package.json ] && [ ! -d node_modules ]; then
  echo "[entrypoint] Installing Node dependencies (optional)..."
  npm install || echo "[entrypoint] npm install failed; continuing"
fi

if [ -f package.json ] && [ ! -f public/build/manifest.json ]; then
  echo "[entrypoint] Building frontend assets (optional)..."
  npm run build || echo "[entrypoint] npm run build failed; continuing"
fi

php artisan key:generate --force --no-interaction || true

until php artisan migrate --force --seed --no-interaction; do
  echo "[entrypoint] Waiting for database..."
  sleep 3
done

echo "[entrypoint] Starting: $*"
exec "$@"

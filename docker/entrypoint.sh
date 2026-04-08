#!/usr/bin/env sh
set -e

cd /var/www

if [ ! -f .env ]; then
  cp .env.example .env
fi

if [ ! -f vendor/autoload.php ]; then
  echo "[entrypoint] Installing Composer dependencies..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ ! -d node_modules ]; then
  echo "[entrypoint] Installing Node dependencies..."
  npm install
fi

if [ ! -f public/build/manifest.json ]; then
  echo "[entrypoint] Building frontend assets..."
  npm run build
fi

php artisan key:generate --force --no-interaction

until php artisan migrate --force --seed --no-interaction; do
  echo "[entrypoint] Waiting for database..."
  sleep 3
done

echo "[entrypoint] Bootstrapping finished. Starting php-fpm..."
exec php-fpm

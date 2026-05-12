#!/bin/bash
set -e

cd /var/www

echo "Waiting for MySQL..."
until php -r "
  try {
    \$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    echo 'ok';
  } catch (Exception \$e) {
    exit(1);
  }
" 2>/dev/null | grep -q ok; do
  echo "  still waiting..."
  sleep 2
done
echo "MySQL is ready."

php artisan migrate --force
php artisan db:seed --force 2>/dev/null || true
php artisan storage:link --force 2>/dev/null || true

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

php-fpm -D

echo "App running"
nginx -g "daemon off;"
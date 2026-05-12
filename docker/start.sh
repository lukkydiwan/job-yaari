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

# Run migrations
php artisan migrate --force

# Seed ONLY if users table is empty (first deploy only)
USER_COUNT=$(php -r "
  \$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
  echo \$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
")

if [ "$USER_COUNT" = "0" ]; then
  echo "Fresh database detected, seeding..."
  php artisan db:seed --force
else
  echo "Database already seeded, skipping."
fi

php artisan storage:link --force 2>/dev/null || true
php artisan config:cache
php artisan route:cache

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

php-fpm -D

echo "App is running!"
nginx -g "daemon off;"
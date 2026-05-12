#!/bin/bash
set -e

cd /var/www

# Print env vars for debugging (remove after it works)
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"

# Copy env vars into .env so Laravel can read them
echo "APP_KEY=$APP_KEY" >> .env
echo "DB_CONNECTION=mysql" >> .env
echo "DB_HOST=$DB_HOST" >> .env
echo "DB_PORT=$DB_PORT" >> .env
echo "DB_DATABASE=$DB_DATABASE" >> .env
echo "DB_USERNAME=$DB_USERNAME" >> .env
echo "DB_PASSWORD=$DB_PASSWORD" >> .env

php artisan config:clear

echo "Waiting for MySQL..."
RETRIES=30
COUNT=0
until php -r "
  try {
    \$pdo = new PDO(
      'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
      getenv('DB_USERNAME'),
      getenv('DB_PASSWORD')
    );
    echo 'ok';
  } catch (Exception \$e) {
    echo 'error: ' . \$e->getMessage();
    exit(1);
  }
" 2>/dev/null | grep -q ok; do
  COUNT=$((COUNT+1))
  if [ $COUNT -ge $RETRIES ]; then
    echo "Could not connect to MySQL after $RETRIES attempts. Check DB credentials."
    exit 1
  fi
  echo "  still waiting... ($COUNT/$RETRIES)"
  sleep 3
done

echo "MySQL connected!"

php artisan migrate --force

USER_COUNT=$(php -r "
  \$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
  echo \$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
")

if [ "$USER_COUNT" = "0" ]; then
  echo "Seeding database..."
  php artisan db:seed --force
else
  echo "Already seeded, skipping."
fi

php artisan storage:link --force 2>/dev/null || true
php artisan config:cache
php artisan route:cache

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

php-fpm -D
nginx -g "daemon off;"
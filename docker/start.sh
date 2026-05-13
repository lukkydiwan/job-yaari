#!/bin/bash
set -e

cd /var/www

# Rewrite .env cleanly from Render environment variables
cat > .env << EOF
APP_NAME="JobYaari Blog"
APP_ENV=production
APP_DEBUG=false
APP_URL=$APP_URL
APP_KEY=$APP_KEY

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=$DB_HOST
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD

CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=false

BROADCAST_DRIVER=log
EOF

php artisan config:clear

echo "Waiting for MySQL at $DB_HOST:$DB_PORT..."
RETRIES=30
COUNT=0
until php -r "
  try {
    \$pdo = new PDO(
      'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
      getenv('DB_USERNAME'),
      getenv('DB_PASSWORD'),
      [PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false]
    );
    echo 'ok';
  } catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage();
    exit(1);
  }
" 2>/dev/null | grep -q ok; do
  COUNT=$((COUNT+1))
  if [ $COUNT -ge $RETRIES ]; then
    echo "Could not connect to MySQL after $RETRIES attempts."
    exit 1
  fi
  echo "Still waiting... ($COUNT/$RETRIES)"
  sleep 3
done

echo "MySQL connected!"

# Run migrations
php artisan migrate --force

# Seed only on first deploy
USER_COUNT=$(php -r "
  try {
    \$pdo = new PDO(
      'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
      getenv('DB_USERNAME'),
      getenv('DB_PASSWORD'),
      [PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false]
    );
    echo \$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
  } catch (Exception \$e) {
    echo '0';
  }
" 2>/dev/null || echo "0")

if [ "$USER_COUNT" = "0" ]; then
  echo "Fresh database, seeding..."
  php artisan db:seed --force
else
  echo "Database already seeded ($USER_COUNT users found), skipping."
fi

# Storage symlink
php artisan storage:link --force 2>/dev/null || true

# Cache config and routes for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Start PHP-FPM in background
php-fpm -D

echo "App is live!"

# Start Nginx in foreground
nginx -g "daemon off;"
#!/bin/bash
set -e

# 1. Handle Port Binding for Render
# If Render provides a PORT, swap it into Nginx config. Default to 80 if not set.
DEPLOY_PORT=${PORT:-80}
echo "Configuring Nginx to listen on port $DEPLOY_PORT"
sed -i "s/%PORT%/$DEPLOY_PORT/g" /etc/nginx/sites-available/default

cd /var/www

# 2. Sync Environment Variables
# Laravel reads system env vars directly, but we'll ensure they are cached.
php artisan config:clear

# 3. Enhanced Database Check
echo "Waiting for MySQL connection to $DB_HOST:$DB_PORT..."
RETRIES=20
COUNT=0

until php -r "
  try {
    \$pdo = new PDO(
      'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
      getenv('DB_USERNAME'),
      getenv('DB_PASSWORD'),
      [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5]
    );
    exit(0);
  } catch (Exception \$e) {
    fwrite(STDERR, 'Still waiting... Error: ' . \$e->getMessage() . PHP_EOL);
    exit(1);
  }
"; do
  COUNT=$((COUNT+1))
  if [ $COUNT -ge $RETRIES ]; then
    echo "CRITICAL: Could not connect to MySQL. Check Railway Public TCP settings."
    exit 1
  fi
  sleep 3
done

echo "MySQL connected!"

# 4. Database Operations
php artisan migrate --force

# Seed only if users table is empty
USER_COUNT=$(php -r "
  \$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
  echo \$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
")

if [ "$USER_COUNT" = "0" ]; then
  echo "Seeding database..."
  php artisan db:seed --force
fi

# 5. Optimization & Permissions
php artisan storage:link --force || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 6. Start Services
echo "Starting PHP-FPM and Nginx..."
php-fpm -D
nginx -g "daemon off;"
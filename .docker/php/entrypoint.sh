#!/bin/sh
composer install --working-dir=/app
if [ -z `cat /app/.env.test | grep "DOCKER_BASE_URL"` ]; then
  echo "DOCKER_BASE_URL=http://nginx:81/" >> /app/.env.test
fi
php /app/bin/console doctrine:database:create --if-not-exists
php /app/bin/console doctrine:schema:update --force
php /app/bin/console lexik:jwt:generate-keypair --overwrite

echo "Initialize tests database..."
php /app/bin/console doctrine:database:create --env=test --if-not-exists
php /app/bin/console doctrine:schema:update  --env=test --force
php /app/bin/console app:demo  --env=test

echo "Set cache directory permissions"
chmod 777 -Rf /app/var/cache

exec $@

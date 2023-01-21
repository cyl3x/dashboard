#!/bin/ash

## generate database
touch /app/var/dashboard.sqlite
rm -rf /app/database/dashboard.sqlite
ln -s /app/var/dashboard.sqlite /app/database

## check for .env file and generate app keys if missing
if [ -f /app/var/.env ]; then
  echo "external vars exist."
  rm -rf /app/.env
else
  echo "external vars don't exist."
  rm -rf /app/.env
  touch /app/var/.env

  ## manually generate a key
  if [ -z $APP_KEY ]; then
    echo -e "Generating key."
    APP_KEY=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
    echo -e "Generated app key: $APP_KEY"
    echo -e "APP_KEY=$APP_KEY" >> /app/var/.env
  else
    echo -e "APP_KEY exists in environment, using that."
    echo -e "APP_KEY=$APP_KEY" >> /app/var/.env
  fi

  if [ -z $JWT_SECRET ]; then
    echo -e "Generating secret."
    JWT_SECRET=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 64 | head -n 1)
    echo -e "Generated jwt secret: $JWT_SECRET"
    echo -e "JWT_SECRET=$JWT_SECRET" >> /app/var/.env
  else
    echo -e "JWT_SECRET exists in environment, using that."
    echo -e "JWT_SECRET=$JWT_SECRET" >> /app/var/.env
  fi
fi

if [ ! -d /app/var/app ]; then
  mkdir -p /app/var/app
fi

ln -s /app/var/app /app/storage/app
ln -s /app/var/.env /app/

php artisan migrate --force

chown www:www -R /app/var

exec "$@" 

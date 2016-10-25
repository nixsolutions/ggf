#!/bin/bash

DIR="$( cd "$( dirname "$0" )" && pwd )"

#cd $DIR/../

# Install npm dependencies
#echo "NPM Install [backend]"
#npm install  --allow-root

#cd $DIR/../resources/frontend

# Install npm dependencies
#echo "NPM Install [frontend]"
#npm install  --allow-root

cd $DIR/../

echo "Migrations status before"
php artisan migrate:status
echo "Run migrations"
php artisan migrate || exit 1
echo "Migrations status after"
php artisan migrate:status

#sh bin/build.sh

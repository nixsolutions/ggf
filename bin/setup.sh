#!/bin/bash

DIR="$( cd "$( dirname "$0" )" && pwd )"

cd $DIR/../

# Install npm dependencies
echo "Create .env"
cp .env.example .env

echo "Composer install"
#install composer
#case "$env" in
#    *)
        echo  "Install Composer"
        chmod -v +x ${DIR}/composer.sh
#        ${DIR}/composer.sh ${env}
        ${DIR}/composer.sh
#       ;;
#esac
#composer install --no-interaction --prefer-source

echo "Generate application key"
php artisan key:generate

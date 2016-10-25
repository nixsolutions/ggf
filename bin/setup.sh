#!/bin/bash

DIR="$( cd "$( dirname "$0" )" && pwd )"

cd $DIR/../

# Install npm dependencies
echo "Create .env"
cp .env.example .env

echo "Composer install"
#install composer

        echo  "Install Composer"
        chmod -v +x ${DIR}/composer.sh
        ${DIR}/composer.sh

echo "Generate application key"
php artisan key:generate
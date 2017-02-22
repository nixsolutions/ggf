#!/bin/bash

DIR="$( cd "$( dirname "$0" )" && pwd )"

while getopts e: flag; do
    case ${flag} in
        e)
            env=$OPTARG;
            ;;
        ?)
            echo "$OPTARG"
            exit;
            ;;
    esac
done

cd $DIR/../
echo "Setup EVN = ${env}"

# Copy .env config for current environment
echo "Create .env (Copy .env config for current environment )"
if [ ! -f .env.${env}.example ]; then
    echo "File .env.${env}.example not found! Set right env or create config file."
    exit;
fi
cp .env.${env}.example .env

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

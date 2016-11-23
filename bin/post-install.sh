#!/bin/bash

DIR="$( cd "$( dirname "$0" )" && pwd )"

cd $DIR/../

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

case "$env" in
    testing)
        cp -v .env.testing .env
        ;;
    *)
        cp -v .env.local .env
        ;;
esac

echo "Composer install"
chmod -v +x ${DIR}/composer.sh
${DIR}/composer.sh ${env}

echo "Generate application key"
php artisan key:generate

echo "Change permissions"

mkdir -p ${DIR}/../storage/debugbar/
chmod -v a+w ${DIR}/../storage/debugbar/

mkdir -p ${DIR}/../storage/framework/
chmod -v a+w ${DIR}/../storage/framework/

mkdir -p ${DIR}/../storage/framework/cache/
chmod -v a+w ${DIR}/../storage/framework/cache/

mkdir -p ${DIR}/../storage/framework/sessions/
chmod -v a+w ${DIR}/../storage/framework/sessions/

mkdir -p ${DIR}/../storage/framework/views/
chmod -v a+w ${DIR}/../storage/framework/views/

mkdir -p ${DIR}/../storage/logs/
chmod -v a+w ${DIR}/../storage/logs/

mkdir -p ${DIR}/../storage/api-docs/
chmod -v a+w ${DIR}/../storage/api-docs/

echo "Migrations status before"
php artisan migrate:status
echo "Run migrations"
php artisan migrate || exit 1
echo "Migrations status after"
php artisan migrate:status

# Install npm dependencies
echo "NPM Install [backend]"
case "$env" in
    vagrant)
        npm install  --allow-root
        ;;
    *)
        npm install
        ;;
esac

cd $DIR/../resources/frontend

# Install npm dependencies
echo "NPM Install [frontend]"
case "$env" in
    vagrant)
        npm install  --allow-root
        ;;
    *)
        npm install
        ;;
esac

cd $DIR/../

sh bin/build.sh

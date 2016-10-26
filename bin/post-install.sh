#!/bin/bash

DIR="$( cd "$( dirname "$0" )" && pwd )"

#cd $DIR/../

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

# Install npm dependencies
echo "NPM Install [backend]"
npm install  --allow-root

cd $DIR/../resources/frontend

# Install npm dependencies
echo "NPM Install [frontend]"
npm install  --allow-root

cd $DIR/../

echo "Migrations status before"
php artisan migrate:status
echo "Run migrations"
php artisan migrate || exit 1
echo "Migrations status after"
php artisan migrate:status

sh bin/build.sh

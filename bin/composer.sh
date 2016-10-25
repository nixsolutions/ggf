#!/bin/bash

DIR="$( cd "$( dirname "$0" )" && pwd )"

if [ ! -f $DIR/composer.phar ]
then
    curl -sS https://getcomposer.org/installer | php -- --install-dir=$DIR
else
    php $DIR/composer.phar self-update --profile
fi

php $DIR/composer.phar -V
php $DIR/composer.phar diagnose --profile

action="$2"
if [ "$action" != "update" ]
then
    action=install
fi

case "$1" in
    *live*) dev="--no-dev" ;;
    *) dev="--dev" ;;
esac

php $DIR/composer.phar $action $dev --prefer-dist --optimize-autoloader --working-dir $DIR/.. --profile
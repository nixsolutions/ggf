#!/bin/bash

DIR="$( cd "$( dirname "$0" )" && pwd )"

cd $DIR/../

# Install npm dependencies
echo "NPM Install [backend]"
npm install  --allow-root

cd $DIR/../resources/frontend

# Install npm dependencies
echo "NPM Install [frontend]"
npm install  --allow-root

cd $DIR/../

php artisan migrate

sh bin/build.sh

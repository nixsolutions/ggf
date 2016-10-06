#!/bin/bash

echo "Install npm version we need"
npm install -g npm@latest-2 > dev\null

su - vagrant -c "EMBER_ENV=development sh ggf/bin/post-install.sh"
#!/bin/bash

echo "Install npm version we need"
npm install -g npm@latest-2 > dev\null

echo "Add external access to Postgresql DB"
echo 'host    all    all    192.168.10.1/32    trust' >> /etc/postgresql/9.5/main/pg_hba.conf
sudo service postgresql restart

su - vagrant -c "EMBER_ENV=development sh ggf/bin/post-install.sh"
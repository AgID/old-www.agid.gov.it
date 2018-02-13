#!/usr/bin/env bash

read -p "Provide username that hosts this project [agid]: " USER
if [ -z "$USER" ]; then
  USER=agid;
fi

read -p "Provide the absolute path to deploy to [/var/www/html]: " DEPLOY_TO
if [ -z "$DEPLOY_TO" ]; then
  DEPLOY_TO=/var/www/html;
fi

# Write config file for this project
sed "s#@USER@#${USER}#g;s#@DEPLOY_TO@#${DEPLOY_TO}#g" deploy.yml.template > deploy.yml
echo "Deploy file generated with provided parameters."
sed "s#@DEPLOY_TO@#${DEPLOY_TO}#g" rollback.yml.template > rollback.yml
echo "Rollback file generated with provided parameters."
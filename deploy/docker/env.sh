#!/bin/bash

set -x

# Use Jenkins Terraform vars to set .env urls
SUBDOMAIN="${TF_VAR_aws_subdomain}"
DOMAIN="${TF_VAR_aws_domain}"
NEW_URL="https://${SUBDOMAIN}.${DOMAIN}"

cd ../../

# Set .env urls
sed -i "s|APP_URL=.*|APP_URL=${NEW_URL}|" .env
sed -i "s|VITE_APP_URL=.*|VITE_APP_URL=${NEW_URL}|" .env
sed -i "s|ASSET_URL=.*|ASSET_URL=${NEW_URL}|" .env
sed -i "s|FORCE_HTTPS=.*|FORCE_HTTPS=true|" .env

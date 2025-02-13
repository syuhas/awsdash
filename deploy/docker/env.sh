#!/bin/bash

set -x  # Enable debugging mode

# Use Terraform environment variables (set in Jenkins or shell)
SUBDOMAIN="${TF_VAR_aws_subdomain}"
DOMAIN="${TF_VAR_aws_domain}"

# SUBDOMAIN="s4"
# DOMAIN="digitalsteve.net"
NEW_URL="https://${SUBDOMAIN}.${DOMAIN}"

cd ../../  # Ensure script exits if cd fails
pwd
ls -la
# Verify the .env file exists
if [[ ! -f .env ]]; then
    echo ".env file not found!"
    exit 1
fi

# Modify .env file two directories up
sed -i "s|APP_URL=.*|APP_URL=${NEW_URL}|" .env
sed -i "s|VITE_APP_URL=.*|VITE_APP_URL=${NEW_URL}|" .env
sed -i "s|ASSET_URL=.*|ASSET_URL=${NEW_URL}|" .env
sed -i "s|FORCE_HTTPS=.*|FORCE_HTTPS=true|" .env

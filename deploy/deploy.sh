#!/bin/bash

set -x

INSTANCE_PUBLIC_DNS="${PUBLIC_DNS}"
REMOTE_USER="${USER}"

if [ -z "$INSTANCE_PUBLIC_DNS" ]; then
    echo "Could not find DNS or user for SSH connection"
    exit 1
fi

echo "Remote user: {$REMOTE_USER}"
echo "Instance DNS: {$INSTANCE_PUBLIC_DNS}"

echo "Copying project files to remote server..."
scp -o StrictHostKeyChecking=no -r ./src "$REMOTE_USER@$INSTANCE_PUBLIC_DNS:/tmp"

echo "Connecting to remote server via SSH..."


ssh -o StrictHostKeyChecking=no "$REMOTE_USER@$INSTANCE_PUBLIC_DNS" << 'EOF'

sudo dnf update -y
sudo dnf upgrade -y

sudo dnf install composer -y

sudo dnf install httpd -y

sudo dnf install php -y

sudo mkdir -p /var/www/html/app
sudo cp -r /tmp/src/* /var/www/html/app
sudo chown -R apache:apache /var/www/html/app

sudo systemctl enable httpd
sudo systemctl start httpd

cd /var/www/html/app
sudo composer require aws/aws-sdk-php
sudo composer require twig/twig

sudo systemctl restart httpd

php -v 

EOF

echo "Deployment complete!"
    


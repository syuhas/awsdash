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

sudo yum update -y
sudo yum upgrade -y

sudo yum install composer -y

sudo yum install httpd -y

sudo yum install php -y

sudo mkdir -p /var/www/html/app
sudo cp -r /tmp/src/* /var/www/html/app
sudo chown -R apache:apache /var/www/html/app

sudo systemctl enable httpd
sudo systemctl start httpd

cd /var/www/html/app
sudo composer require aws/aws-sdk-php
sudo composer require twig/twig


sudo sed -i 's|DocumentRoot "/var/www/html"|DocumentRoot "/var/www/html/app"|' /etc/httpd/conf/httpd.conf
sudo sed -i 's|DirectoryIndex index.html.*|DirectoryIndex app/s3.html|' /etc/httpd/conf/httpd.conf

sudo systemctl restart httpd

php -v 

EOF

echo "Deployment complete!"
    


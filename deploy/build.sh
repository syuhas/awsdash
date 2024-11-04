#!/bin/bash
set -x

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
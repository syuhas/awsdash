#syntax docker/dockerfile:1.0.0

FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip

COPY . /usr/src/myapp

WORKDIR /usr/src/myapp



RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer



RUN composer require aws/aws-sdk-php
RUN composer require twig/twig

RUN composer install

RUN ls -a -R /usr/src/myapp

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "./src/s3.php" ]

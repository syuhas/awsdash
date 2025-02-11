#syntax docker/dockerfile:1.0.0

FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    nodejs \
    npm \
    nginx



RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN chown -R www-data:www-data /var/www

COPY ./deploy/docker/default.conf /etc/nginx/sites-available/default
COPY ./deploy/docker/default.conf /etc/nginx/sites-enabled/default
COPY ./deploy/docker/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]


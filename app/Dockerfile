FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip curl zip \
    libpq-dev libzip-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN npm install && npm run build

COPY docker-entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 10000

CMD ["/entrypoint.sh"]
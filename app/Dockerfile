FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    zip \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app

COPY . .


ENV APP_ENV=prod
ENV APP_DEBUG=0

RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN npm install

RUN touch /var/www/app/.env && chmod 644 /var/www/app/.env

RUN npm run build

RUN php bin/console cache:clear --env=prod || true

RUN php bin/console doctrine:migrations:migrate --no-interaction --env=prod || true

EXPOSE 10000

#CMD php -S 0.0.0.0:$PORT -t public

#COPY docker-entrypoint.sh /docker-entrypoint.sh
#RUN chmod +x /docker-entrypoint.sh

#ENTRYPOINT ["/docker-entrypoint.sh"]
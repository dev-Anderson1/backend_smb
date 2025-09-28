FROM php:8.1-fpm

# Instalar dependências do sistema e Node.js 20.x via NodeSource
RUN apt-get update && apt-get install -y curl zip unzip git libpng-dev libjpeg-dev libonig-dev libxml2-dev libzip-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Copiar composer do container oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install
RUN npm install

RUN chown -R www-data:www-data /var/www

CMD ["php-fpm"]

FROM php:8.1.6-cli-alpine
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /src
ENTRYPOINT [ "php", "-S", "0.0.0.0:8000", "-t", "/src/public" ] 

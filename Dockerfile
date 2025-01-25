# Берём образ Апача
FROM php:8.2-apache

# Устанавливаем необходимые расширения
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql 

RUN a2enmod rewrite

# Копируем проект в контейнер
COPY ./src /var/www/html

# Устанавливаем необходимые разрешения
RUN chown -R www-data:www-data /var/www/html

# Открываем порт 80
EXPOSE 80

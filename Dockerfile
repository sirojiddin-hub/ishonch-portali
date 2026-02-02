# PHP 8.1 va Apache serverini o'rnatamiz
FROM php:8.1-apache

# Kerakli kutubxonalarni o'rnatamiz (PostgreSQL uchun)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql

# Apache sozlamalari (DocumentRoot ni public papkaga yo'naltiramiz)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf.conf

# Mod Rewrite ni yoqamiz (Laravel route lari ishlashi uchun)
RUN a2enmod rewrite

# Composer ni o'rnatamiz
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Loyiha fayllarini serverga ko'chiramiz
WORKDIR /var/www/html
COPY . .

# Kutubxonalarni o'rnatamiz
RUN composer install --no-dev --optimize-autoloader

# Ruxsatlarni to'g'irlaymiz
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Portni ochamiz
EXPOSE 80
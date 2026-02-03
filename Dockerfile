FROM php:8.2-apache

# 1. Kerakli kutubxonalarni o'rnatish
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql zip

# 2. Apache sozlamasi
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# 3. Composer o'rnatish
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Fayllarni ko'chirish
WORKDIR /var/www/html
COPY . .

# 5. Laravel kutubxonalarini o'rnatish
RUN composer install --no-dev --optimize-autoloader

# 6. Ruxsatlarni to'g'irlash
RUN chown -R www-data:www-data storage bootstrap/cache

# 7. Entrypoint skriptini sozlash (MUHIM TUZATISH)
COPY docker-entrypoint.sh /usr/local/bin/
# Windows belgilarini (\r) tozalash:
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
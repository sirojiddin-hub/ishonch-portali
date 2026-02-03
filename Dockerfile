FROM php:8.2-apache

# 1. Kerakli kutubxonalarni o'rnatish
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    && docker-php-ext-install pdo_pgsql zip bcmath intl opcache

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

# 7. HTTPS-ni majburiy qilish (YANGI QO'SHILGAN QISM)
# Bu kod AppServiceProvider-ga forceScheme('https') ni qo'shadi
RUN sed -i '/public function boot(): void/a \        \\URL::forceScheme("https");' app/Providers/AppServiceProvider.php || \
    sed -i '/public function boot()/a \        \\URL::forceScheme("https");' app/Providers/AppServiceProvider.php

# 8. Portni ochish
EXPOSE 80

# 9. ISHGA TUSHIRISH
CMD bash -c "php artisan migrate --force || true && apache2-foreground"
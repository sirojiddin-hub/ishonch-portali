#!/bin/bash

# Xatolik bo'lsa to'xtash
set -e

# Migratsiyani majburiy bajarish
echo "Migratsiya bajarilmoqda..."
php artisan migrate --force

# Keshni tozalash (ixtiyoriy, lekin foydali)
php artisan config:clear
php artisan cache:clear

# Apache serverni ishga tushirish (Asosiy ish)
echo "Apache ishga tushmoqda..."
exec apache2-foreground
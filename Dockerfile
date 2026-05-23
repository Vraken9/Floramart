FROM php:8.2-apache

# 1. Atur port default untuk Cloud Run
ENV PORT=8080

# 2. Instal dependensi dasar dan setup Node.js 20.x (agar Vite/Tailwind sukses di-build)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 3. Instal ektensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 4. Aktifkan mod_rewrite Apache (wajib untuk sistem routing Laravel)
RUN a2enmod rewrite

# 5. Dapatkan Composer versi terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Atur direktori kerja (working directory)
WORKDIR /var/www/html

# 7. Salin semua file proyek dari lokal ke dalam container
COPY . .

# 8. Instal dependensi PHP via Composer (Mode Production)
RUN composer install --no-interaction --no-dev --optimize-autoloader

# 9. Instal dependensi Node.js dan build aset frontend (Tailwind & Alpine)
RUN npm install && npm run build

# 10. Atur hak akses (permissions) untuk folder storage dan cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 11. Ubah DocumentRoot Apache ke folder /public milik Laravel
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 12. Paksa Apache mendengarkan port 8080 sesuai permintaan Cloud Run
RUN sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Mulai server Apache
CMD ["apache2-foreground"]

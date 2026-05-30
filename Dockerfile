# ---------------------------------------------------------
# STAGE 1: Node.js (Vite & Tailwind CSS Build)
# ---------------------------------------------------------
FROM node:20-alpine AS node-builder

WORKDIR /app
COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# ---------------------------------------------------------
# STAGE 2: PHP, Composer, and Nginx setup
# ---------------------------------------------------------
FROM php:8.2-fpm-alpine

# Install system dependencies & Nginx
RUN apk add --no-cache \
    nginx \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring zip bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application source code
COPY . .

# Copy Vite build assets from node-builder
COPY --from=node-builder /app/public/build /var/www/html/public/build

# Install PHP dependencies (no-dev, optimize autoloader)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Configure Nginx
COPY nginx.conf /etc/nginx/http.d/default.conf

# Setup Entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set Directory Permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create Nginx PID and run directories for Alpine
RUN mkdir -p /run/nginx

# Expose port 8080 for Google Cloud Run
EXPOSE 8080

CMD ["/usr/local/bin/entrypoint.sh"]

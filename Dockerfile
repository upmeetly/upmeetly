# Stage 1: Base image for building assets
FROM node:latest AS node_builder

WORKDIR /app

# Copy package.json and package-lock.json for the frontend
COPY package*.json ./

# Install dependencies for Vite
RUN npm install

# Copy the rest of the application to build the assets
COPY . .

# Build the assets using Vite
RUN npm run build

# Stage 2: Base image for PHP
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Copy built assets from the Node.js stage
COPY --from=node_builder /app/public ./public

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port for the application
EXPOSE 9000

# Command to start PHP-FPM
CMD ["php-fpm"]

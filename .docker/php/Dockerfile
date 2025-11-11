FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    zip \
    && pecl install redis \
    && docker-php-ext-install intl pdo pdo_mysql opcache zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy entrypoint script
COPY .docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

#>Fix permissions
#Create a home directory for UID 1000
RUN useradd -u 1000 -m -s /bin/bash dev-user
# Set default user
USER dev-user
#<Fix permissions

# Set working directory
WORKDIR /var/www/n8n_automation

# Copy project files
COPY --chown=dev-user:dev-user . /var/www/n8n_automation

# Install composer dependencies
RUN composer install --no-scripts --no-progress --prefer-dist

# Copy vendor back to a temp location so we can restore it on startup
RUN cp -r vendor /tmp/vendor

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]

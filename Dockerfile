FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libcurl4-openssl-dev libonig-dev \
    && docker-php-ext-install curl mbstring mysqli \
    && a2enmod headers rewrite expires \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY . /var/www/html
COPY docker/apache.conf /etc/apache2/conf-available/portfolio.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/portfolio.ini

RUN a2enconf portfolio \
    && sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:10000>/' /etc/apache2/sites-available/000-default.conf \
    && mkdir -p /var/www/storage/data /var/www/storage/uploads \
    && chown -R www-data:www-data /var/www/storage \
    && rm -rf /var/www/html/assets/uploads/projects \
    && ln -s /var/www/storage/uploads /var/www/html/assets/uploads/projects

ENV APP_DATA_DIR=/var/www/storage/data \
    UPLOAD_STORAGE_DIR=/var/www/storage/uploads

EXPOSE 10000
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 CMD curl --fail http://127.0.0.1:10000/health.php || exit 1

CMD ["apache2-foreground"]

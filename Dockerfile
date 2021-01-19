FROM php:7.4-apache
RUN docker-php-ext-install pdo pdo_mysql mysqli
COPY ./backend /var/www/html
# Enable apache rewrite
COPY ./backend/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www \
    && a2enmod rewrite
# Copy php ini
COPY ./backend/php/php.ini /usr/local/etc/php/php.ini



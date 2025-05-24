FROM php:8-apache

ENV IMAGE_PROJECT_ROOT /var/www/app
ENV IMAGE_PROJECT_PUBLIC /var/www/app/public

RUN a2enmod rewrite

RUN sed -ri -e 's!/var/www/html!${IMAGE_PROJECT_PUBLIC}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${IMAGE_PROJECT_PUBLIC}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN mkdir ${IMAGE_PROJECT_ROOT}
COPY . ${IMAGE_PROJECT_ROOT}

RUN chown -R www-data:www-data ${IMAGE_PROJECT_ROOT}

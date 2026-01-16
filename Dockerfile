# ប្រើ PHP ជាមួយ Apache ដើម្បីឱ្យវាងាយស្រួលដំឡើង
FROM php:8.2-apache

# ១. ដំឡើង System Dependencies ដែលចាំបាច់
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip \
    && docker-php-ext-install pdo pdo_mysql gd

# ២. កំណត់ Document Root ទៅកាន់ folder public របស់ Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# ៣. ចម្លងកូដចូលក្នុង Container
WORKDIR /var/www/html
COPY . .

# ៤. ដំឡើង Composer ឱ្យបានត្រឹមត្រូវ (នេះជាចំណុចដែលបាត់)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# ៥. កំណត់សិទ្ធិឱ្យ Folder storage និង cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# ៦. បើក Port 80
EXPOSE 80
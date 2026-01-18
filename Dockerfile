# ១. ប្រើ PHP ជាមួយ Apache
FROM php:8.2-apache

# ២. ដំឡើង System Dependencies ដែលចាំបាច់
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip curl \
    && docker-php-ext-install pdo pdo_mysql gd

# ៣. ដំឡើង Node.js និង NPM (សម្រាប់ Build Vite)
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# ៤. កំណត់ Document Root ទៅកាន់ folder public របស់ Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# ៥. កំណត់ Folder ធ្វើការ
WORKDIR /var/www/html

# ៦. ចម្លងកូដទាំងអស់ចូល (ត្រូវធ្វើជំហាននេះមុនពេល Run Composer)
COPY . .

# ៧. ដំឡើង Composer ឱ្យបានត្រឹមត្រូវ
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# ៨. ដំឡើង NPM និង Build Vite Assets (ដោះស្រាយបញ្ហា Vite Manifest Not Found)
RUN npm install
RUN npm run build

# ៩. កំណត់សិទ្ធិឱ្យ Folder storage និង cache (សំខាន់សម្រាប់ Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# ១០. បើក Port 80
EXPOSE 80

CMD ["apache2-foreground"]
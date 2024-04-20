# Utiliser une image PHP de base
FROM php:8.3.3-fpm

# Installation de Composer
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    wget \
    unzip && \
    rm -rf /var/lib/apt/lists/* && \
    wget -qO /tmp/installer.php https://getcomposer.org/installer && \
    php /tmp/installer.php --install-dir=/usr/local/bin --filename=composer && \
    rm /tmp/installer.php

# Installation des dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier seulement le fichier composer.json et composer.lock pour profiter du cache Docker
COPY composer.json composer.lock ./

# Installer les dépendances PHP, y compris Laravel
RUN composer install --no-scripts --no-autoloader

# Copier les fichiers du projet dans le conteneur
COPY . .

# Charger les dépendances Composer restantes et générer l'autoloader
RUN composer dump-autoload --optimize

# Exposer le port 8000 (ou tout autre port que vous utilisez pour Laravel)
EXPOSE 8000

# Commande par défaut pour lancer l'application
CMD php artisan serve --host=0.0.0.0 --port=8000

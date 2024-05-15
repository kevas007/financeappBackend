# Utiliser une image PHP de base
FROM php:8.3.3-fpm

# Définir les arguments pour l'utilisateur et l'uid
ARG user
ARG uid

# Mettre à jour les paquets et installer les dépendances nécessaires
RUN apt update && apt install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev && \
    apt clean && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP requises
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copier le binaire de Composer depuis l'image Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ajouter un nouvel utilisateur avec les droits nécessaires
RUN useradd -G www-data,root -u $uid -d /home/$user $user && \
    mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Définir le répertoire de travail
WORKDIR /var/www

# Créer les répertoires nécessaires et changer les permissions
RUN mkdir -p storage bootstrap/cache && \
    chown -R $user:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Passer à l'utilisateur défini par les arguments
USER $user

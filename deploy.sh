#!/usr/bin/env bash
# Script à exécuter sur le serveur après chaque « git pull » pour recrute.moroccoder.com
# Usage : depuis la racine du projet (où se trouve artisan) : bash deploy.sh

set -e

echo "=== Déploiement Laravel ==="

# 1. Mise à jour du code (à lancer vous-même avant : git pull)
# git pull origin main

# 2. Dépendances PHP
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Migrations
php artisan migrate --force

# 4. Lien storage (ignoré si déjà existant)
php artisan storage:link 2>/dev/null || true

# 5. Caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Assets (décommenter si vous build sur le serveur)
# npm ci
# npm run build

echo "=== Déploiement terminé ==="

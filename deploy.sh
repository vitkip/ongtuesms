#!/bin/bash
# ====================================================
# Production Deployment Script - Ongtue SMS
# ====================================================
# ວິທີໃຊ້:
#   1. ຄັ້ງທໍາອິດ: ຕັ້ງຄ່າ .env ໃນ server ກ່ອນ
#   2. ຮັນ: bash deploy.sh
# ====================================================

set -e

BOLD='\033[1m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

log()  { echo -e "${GREEN}[+]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }
fail() { echo -e "${RED}[x]${NC} $1"; exit 1; }

# -------- ດຶງ code ຈາກ git --------
log "Pulling latest code from origin/main..."
git fetch --all
git reset --hard origin/main

# -------- ກວດ .env --------
if [ ! -f ".env" ]; then
    warn ".env not found. Copying from .env.example..."
    cp .env.example .env
    fail "Please fill in .env values then re-run deploy.sh"
fi

# -------- Composer --------
log "Installing PHP dependencies (no-dev)..."
composer install --no-dev --optimize-autoloader --no-interaction

# -------- Key (ສ້າງຖ້າຍັງບໍ່ມີ) --------
if grep -q "^APP_KEY=$" .env; then
    log "Generating application key..."
    php artisan key:generate --force
fi

# -------- Migrate --------
log "Running database migrations..."
php artisan migrate --force

# -------- Storage directories --------
log "Ensuring storage directories exist..."
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/logs
mkdir -p storage/fonts
mkdir -p bootstrap/cache

# -------- Permissions (before artisan commands) --------
log "Setting permissions on storage and bootstrap/cache..."
chmod -R 775 storage bootstrap/cache

# -------- Storage link --------
log "Creating storage symlink..."
php artisan storage:link 2>/dev/null || warn "storage:link already exists"

# -------- Cache --------
log "Caching config, routes, views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

log ""
log "${BOLD}Deployment complete!${NC}"

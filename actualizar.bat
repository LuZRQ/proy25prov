@echo off
echo === Actualizando proyecto Laravel ===

git pull origin main
git diff --name-only HEAD@{1} HEAD
npm install
composer install
npm run dev
php artisan migrate
php artisan config:cache
php artisan route:cache

echo === Listo, Zet. Tu sistema está actualizado ===
pause

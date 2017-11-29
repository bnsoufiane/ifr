@echo off
php artisan migrate:reset
php artisan migrate --package cartalyst/sentry
php artisan migrate
php artisan db:seed

@echo off
cd /d C:\laragon\www\datn
php artisan queue:work --tries=3
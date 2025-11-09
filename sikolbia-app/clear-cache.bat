@echo off
echo Clearing Laravel cache...
echo.

docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear

echo.
echo Cache cleared successfully!
echo.
echo Checking routes...
docker-compose exec app php artisan route:list | findstr backup-restore

echo.
echo Done! Please refresh your browser.
pause

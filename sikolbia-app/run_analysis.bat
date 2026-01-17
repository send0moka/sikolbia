@echo off
cd /d D:\sikolbia\sikolbia-app\database\seeders
echo Running SQL data quality analysis...
php analyze_sql_quality.php
pause

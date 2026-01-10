@echo off
REM Backup script untuk data Beras sebelum update dengan APP3 (Windows)

echo === BACKUP DATA BERAS SEBELUM UPDATE APP3 ===
echo.

set BACKUP_DIR=storage\backups\nbm
set TIMESTAMP=%date:~-4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%
set TIMESTAMP=%TIMESTAMP: =0%
set BACKUP_FILE=%BACKUP_DIR%\beras_backup_%TIMESTAMP%.json

REM Create backup directory
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

REM Backup using PHP
echo Backing up Beras records...
php -r "$data = DB::table('transaksi_nbms')->where('kode_kelompok', '01')->where('kode_komoditi', '0101')->get(); echo 'Found ' . $data->count() . ' records\n'; file_put_contents('%BACKUP_FILE%', $data->toJson(JSON_PRETTY_PRINT)); echo 'Saved to: %BACKUP_FILE%\n';"

echo.
echo === BACKUP COMPLETED ===
dir "%BACKUP_DIR%\beras_backup_%TIMESTAMP%*"
pause

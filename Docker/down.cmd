@echo off
setlocal

cd /d "%~dp0"

echo Stopping containers...

docker-compose -f ./docker-compose.apache.yml -p plib down

echo Containers stopped. You can restart them later with 'docker-compose up'.

endlocal

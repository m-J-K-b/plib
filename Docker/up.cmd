@echo off
setlocal

cd /d "%~dp0"

echo Starting Docker containers...

docker-compose -f ./docker-compose.apache.yml -p plib up -d

echo Containers are up and running.

endlocal

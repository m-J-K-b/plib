@echo off
setlocal

cd /d "%~dp0"

echo Building Docker images...

docker-compose -f ./docker-compose.apache.yml -p plib build

echo Build complete.

endlocal

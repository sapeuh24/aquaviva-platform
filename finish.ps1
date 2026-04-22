$ErrorActionPreference = "Stop"

Write-Host ""
Write-Host "Aquaviva Platform - Finalizando configuracion..." -ForegroundColor Cyan
Write-Host ""

# Bajar contenedores y limpiar volumen vendor que bloqueaba artisan
Write-Host "[1/5] Reiniciando contenedores con config corregida..." -ForegroundColor Yellow
docker compose down
docker compose up -d
if ($LASTEXITCODE -ne 0) { Write-Host "ERROR: Fallo docker compose up." -ForegroundColor Red; exit 1 }
Write-Host "OK" -ForegroundColor Green

# Esperar MySQL
Write-Host "[2/5] Esperando MySQL..." -ForegroundColor Yellow
$attempt = 0
do {
    Start-Sleep -Seconds 3
    $attempt++
    docker compose exec mysql mysqladmin ping -h localhost -u aquaviva -paquaviva_secret --silent 2>$null
} while ($LASTEXITCODE -ne 0 -and $attempt -lt 20)
if ($attempt -ge 20) { Write-Host "ERROR: MySQL no respondio." -ForegroundColor Red; exit 1 }
Write-Host "OK" -ForegroundColor Green

# Instalar vendor dentro del contenedor
Write-Host "[3/5] Instalando dependencias dentro del contenedor..." -ForegroundColor Yellow
docker compose exec app composer install --no-interaction
if ($LASTEXITCODE -ne 0) { Write-Host "ERROR: Fallo composer install." -ForegroundColor Red; exit 1 }
Write-Host "OK" -ForegroundColor Green

# APP_KEY
Write-Host "[4/5] Generando APP_KEY..." -ForegroundColor Yellow
docker compose exec app php artisan key:generate --force
if ($LASTEXITCODE -ne 0) { Write-Host "ERROR: Fallo key:generate." -ForegroundColor Red; exit 1 }
Write-Host "OK" -ForegroundColor Green

# Sanctum + Spatie + migraciones
Write-Host "[5/5] Instalando paquetes y ejecutando migraciones..." -ForegroundColor Yellow
docker compose exec app composer require laravel/sanctum spatie/laravel-permission --no-interaction
if ($LASTEXITCODE -ne 0) { Write-Host "ERROR: Fallo composer require." -ForegroundColor Red; exit 1 }

docker compose exec app php artisan migrate --force
if ($LASTEXITCODE -ne 0) { Write-Host "ERROR: Fallo migrate." -ForegroundColor Red; exit 1 }

docker compose exec app php artisan storage:link
Write-Host "OK" -ForegroundColor Green

Write-Host ""
Write-Host "Entorno listo!" -ForegroundColor Green
Write-Host ""
Write-Host "  API Backend  ->  http://localhost:8000/api/v1" -ForegroundColor Cyan
Write-Host "  phpMyAdmin   ->  http://localhost:8080" -ForegroundColor Cyan
Write-Host "  Mailpit      ->  http://localhost:8025" -ForegroundColor Cyan
Write-Host ""

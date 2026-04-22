$ErrorActionPreference = "Stop"

Write-Host ""
Write-Host "Aquaviva Platform - Inicializando entorno de desarrollo" -ForegroundColor Cyan
Write-Host ""

# Paso 1: Crear Laravel en carpeta temporal (backend-fresh/)
Write-Host "[1/9] Descargando Laravel 11 en carpeta temporal..." -ForegroundColor Yellow

if (Test-Path "backend-fresh") {
    Remove-Item "backend-fresh" -Recurse -Force
}

docker run --rm `
    -v "${PWD}/backend-fresh:/app" `
    -w /app `
    composer:2.7 `
    create-project laravel/laravel . --no-scripts --no-interaction

if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Fallo la instalacion de Laravel." -ForegroundColor Red
    exit 1
}
Write-Host "OK" -ForegroundColor Green

# Paso 2: Copiar esqueleto de Laravel a backend/ (solo lo que no tenemos)
Write-Host "[2/9] Fusionando esqueleto Laravel con arquitectura custom..." -ForegroundColor Yellow

$carpetasCopiar = @("config", "lang", "public", "resources", "storage", "tests")
foreach ($carpeta in $carpetasCopiar) {
    if (-not (Test-Path "backend/$carpeta")) {
        Copy-Item "backend-fresh/$carpeta" "backend/$carpeta" -Recurse -Force
    }
}

$archivosCopiar = @("artisan", ".env.example", ".gitignore", "phpunit.xml")
foreach ($archivo in $archivosCopiar) {
    if (-not (Test-Path "backend/$archivo")) {
        Copy-Item "backend-fresh/$archivo" "backend/$archivo" -Force
    }
}

# bootstrap/providers.php es especifico de Laravel 11 y necesario
if (-not (Test-Path "backend/bootstrap/providers.php")) {
    Copy-Item "backend-fresh/bootstrap/providers.php" "backend/bootstrap/providers.php" -Force
}

# bootstrap/cache/ es necesario para Laravel
New-Item -ItemType Directory -Force -Path "backend/bootstrap/cache" | Out-Null

# Usar el composer.json de Laravel como base (el nuestro era incompleto)
Copy-Item "backend-fresh/composer.json" "backend/composer.json" -Force
Copy-Item "backend-fresh/composer.lock" "backend/composer.lock" -Force

Write-Host "OK" -ForegroundColor Green

# Paso 3: Eliminar carpeta temporal
Write-Host "[3/9] Limpiando carpeta temporal..." -ForegroundColor Yellow
Remove-Item "backend-fresh" -Recurse -Force
Write-Host "OK" -ForegroundColor Green

# Paso 4: Instalar dependencias PHP en backend/
Write-Host "[4/9] Instalando dependencias PHP (composer install)..." -ForegroundColor Yellow

docker run --rm `
    -v "${PWD}/backend:/app" `
    -w /app `
    composer:2.7 `
    install --no-scripts --no-interaction

if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Fallo composer install." -ForegroundColor Red
    exit 1
}
Write-Host "OK" -ForegroundColor Green

# Paso 5: Construir imagen Docker
Write-Host "[5/9] Construyendo imagen PHP 8.2..." -ForegroundColor Yellow
docker compose build
if ($LASTEXITCODE -ne 0) { Write-Host "ERROR: Fallo el build Docker." -ForegroundColor Red; exit 1 }
Write-Host "OK" -ForegroundColor Green

# Paso 6: Levantar contenedores
Write-Host "[6/9] Levantando contenedores..." -ForegroundColor Yellow
docker compose up -d
if ($LASTEXITCODE -ne 0) { Write-Host "ERROR: Fallo docker compose up." -ForegroundColor Red; exit 1 }
Write-Host "OK" -ForegroundColor Green

# Paso 7: Esperar MySQL
Write-Host "[7/9] Esperando MySQL..." -ForegroundColor Yellow
$attempt = 0
do {
    Start-Sleep -Seconds 3
    $attempt++
    docker compose exec mysql mysqladmin ping -h localhost -u aquaviva -paquaviva_secret --silent 2>$null
} while ($LASTEXITCODE -ne 0 -and $attempt -lt 20)

if ($attempt -ge 20) { Write-Host "ERROR: MySQL no respondio." -ForegroundColor Red; exit 1 }
Write-Host "OK" -ForegroundColor Green

# Paso 8: Configurar .env y APP_KEY
Write-Host "[8/9] Configurando .env y generando APP_KEY..." -ForegroundColor Yellow
Copy-Item "docker/.env.docker" "backend/.env" -Force
docker compose exec app php artisan key:generate --force
if ($LASTEXITCODE -ne 0) { Write-Host "ERROR: Fallo key:generate." -ForegroundColor Red; exit 1 }
Write-Host "OK" -ForegroundColor Green

# Paso 9: Paquetes adicionales + migraciones
Write-Host "[9/9] Instalando Sanctum, Spatie y ejecutando migraciones..." -ForegroundColor Yellow

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

$ErrorActionPreference = "Stop"

Write-Host ""
Write-Host "Aquaviva Platform - Continuando configuracion..." -ForegroundColor Cyan
Write-Host ""

# Paso 1: Copiar esqueleto de Laravel a backend/ (solo lo que no tenemos)
Write-Host "[1/7] Fusionando esqueleto Laravel con arquitectura custom..." -ForegroundColor Yellow

$carpetasCopiar = @("config", "public", "resources", "storage", "tests")
foreach ($carpeta in $carpetasCopiar) {
    if (Test-Path "backend-fresh/$carpeta") {
        if (-not (Test-Path "backend/$carpeta")) {
            Copy-Item "backend-fresh/$carpeta" "backend/$carpeta" -Recurse -Force
            Write-Host "  Copiado: $carpeta/" -ForegroundColor Gray
        } else {
            Write-Host "  Omitido (ya existe): $carpeta/" -ForegroundColor Gray
        }
    }
}

$archivosCopiar = @("artisan", ".env.example", ".gitignore", "phpunit.xml")
foreach ($archivo in $archivosCopiar) {
    if (Test-Path "backend-fresh/$archivo") {
        if (-not (Test-Path "backend/$archivo")) {
            Copy-Item "backend-fresh/$archivo" "backend/$archivo" -Force
            Write-Host "  Copiado: $archivo" -ForegroundColor Gray
        } else {
            Write-Host "  Omitido (ya existe): $archivo" -ForegroundColor Gray
        }
    }
}

if ((Test-Path "backend-fresh/bootstrap/providers.php") -and (-not (Test-Path "backend/bootstrap/providers.php"))) {
    Copy-Item "backend-fresh/bootstrap/providers.php" "backend/bootstrap/providers.php" -Force
    Write-Host "  Copiado: bootstrap/providers.php" -ForegroundColor Gray
}

New-Item -ItemType Directory -Force -Path "backend/bootstrap/cache" | Out-Null

if (Test-Path "backend-fresh/composer.json") {
    Copy-Item "backend-fresh/composer.json" "backend/composer.json" -Force
    Write-Host "  Copiado: composer.json" -ForegroundColor Gray
}
if (Test-Path "backend-fresh/composer.lock") {
    Copy-Item "backend-fresh/composer.lock" "backend/composer.lock" -Force
}

Write-Host "OK" -ForegroundColor Green

# Paso 2: Eliminar carpeta temporal
Write-Host "[2/7] Limpiando carpeta temporal..." -ForegroundColor Yellow
if (Test-Path "backend-fresh") {
    Remove-Item "backend-fresh" -Recurse -Force
}
Write-Host "OK" -ForegroundColor Green

# Paso 3: Instalar vendor/ en backend/
Write-Host "[3/7] Instalando dependencias PHP (composer install)..." -ForegroundColor Yellow

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

# Paso 4: Construir imagen y levantar contenedores
Write-Host "[4/7] Construyendo imagen PHP 8.2 y levantando contenedores..." -ForegroundColor Yellow
docker compose build
if ($LASTEXITCODE -ne 0) { Write-Host "ERROR: Fallo docker build." -ForegroundColor Red; exit 1 }

docker compose up -d
if ($LASTEXITCODE -ne 0) { Write-Host "ERROR: Fallo docker compose up." -ForegroundColor Red; exit 1 }
Write-Host "OK" -ForegroundColor Green

# Paso 5: Esperar MySQL
Write-Host "[5/7] Esperando MySQL..." -ForegroundColor Yellow
$attempt = 0
do {
    Start-Sleep -Seconds 3
    $attempt++
    docker compose exec mysql mysqladmin ping -h localhost -u aquaviva -paquaviva_secret --silent 2>$null
} while ($LASTEXITCODE -ne 0 -and $attempt -lt 20)

if ($attempt -ge 20) { Write-Host "ERROR: MySQL no respondio." -ForegroundColor Red; exit 1 }
Write-Host "OK" -ForegroundColor Green

# Paso 6: .env y APP_KEY
Write-Host "[6/7] Configurando .env y generando APP_KEY..." -ForegroundColor Yellow
Copy-Item "docker/.env.docker" "backend/.env" -Force
docker compose exec app php artisan key:generate --force
if ($LASTEXITCODE -ne 0) { Write-Host "ERROR: Fallo key:generate." -ForegroundColor Red; exit 1 }
Write-Host "OK" -ForegroundColor Green

# Paso 7: Sanctum, Spatie y migraciones
Write-Host "[7/7] Instalando paquetes y ejecutando migraciones..." -ForegroundColor Yellow

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

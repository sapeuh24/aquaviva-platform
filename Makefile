# ─────────────────────────────────────────────────────────────────────────────
# Aquaviva Platform — Comandos de desarrollo
# Uso: make <comando>
# ─────────────────────────────────────────────────────────────────────────────

DOCKER_COMPOSE = docker compose
APP_CONTAINER  = aquaviva_app

.PHONY: help init up down restart logs shell artisan composer migrate fresh seed test

help:
	@echo ""
	@echo "  Aquaviva Platform — Comandos disponibles"
	@echo ""
	@echo "  make init        Primer arranque: instala Laravel, configura .env y migra"
	@echo "  make up          Levanta todos los contenedores"
	@echo "  make down        Detiene y elimina los contenedores"
	@echo "  make restart     Reinicia los contenedores"
	@echo "  make logs        Muestra logs en tiempo real"
	@echo "  make shell       Abre una shell en el contenedor PHP"
	@echo "  make artisan     Ejecuta un comando artisan: make artisan CMD='migrate'"
	@echo "  make composer    Ejecuta un comando composer: make composer CMD='require paquete'"
	@echo "  make migrate     Ejecuta las migraciones"
	@echo "  make fresh       Resetea la DB y re-ejecuta todas las migraciones"
	@echo "  make seed        Ejecuta los seeders"
	@echo "  make test        Ejecuta los tests con Pest"
	@echo ""

# ─── Primer arranque ──────────────────────────────────────────────────────────
init:
	@echo ">>> Instalando Laravel 11 en backend/..."
	docker run --rm \
		-v "$(CURDIR)/backend:/app" \
		-w /app \
		composer:2.7 \
		create-project laravel/laravel . --no-scripts --no-interaction
	@echo ">>> Copiando archivos de arquitectura custom..."
	$(DOCKER_COMPOSE) build
	@echo ">>> Levantando contenedores..."
	$(DOCKER_COMPOSE) up -d
	@echo ">>> Esperando que MySQL este listo..."
	sleep 8
	@echo ">>> Configurando .env..."
	cp backend/.env.example backend/.env
	cp docker/.env.docker backend/.env
	@echo ">>> Generando clave de aplicacion..."
	$(DOCKER_COMPOSE) exec app php artisan key:generate
	@echo ">>> Instalando dependencias adicionales..."
	$(DOCKER_COMPOSE) exec app composer require laravel/sanctum spatie/laravel-permission --no-interaction
	@echo ">>> Ejecutando migraciones..."
	$(DOCKER_COMPOSE) exec app php artisan migrate --force
	@echo ">>> Creando symlink de storage..."
	$(DOCKER_COMPOSE) exec app php artisan storage:link
	@echo ""
	@echo " Listo. La API esta disponible en http://localhost:8000/api/v1"
	@echo " phpMyAdmin: http://localhost:8080"
	@echo " Mailpit:    http://localhost:8025"
	@echo ""

# ─── Contenedores ─────────────────────────────────────────────────────────────
up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

restart:
	$(DOCKER_COMPOSE) restart

logs:
	$(DOCKER_COMPOSE) logs -f app nginx

# ─── Desarrollo ───────────────────────────────────────────────────────────────
shell:
	$(DOCKER_COMPOSE) exec app bash

artisan:
	$(DOCKER_COMPOSE) exec app php artisan $(CMD)

composer:
	$(DOCKER_COMPOSE) exec app composer $(CMD)

migrate:
	$(DOCKER_COMPOSE) exec app php artisan migrate

fresh:
	$(DOCKER_COMPOSE) exec app php artisan migrate:fresh --seed

seed:
	$(DOCKER_COMPOSE) exec app php artisan db:seed

test:
	$(DOCKER_COMPOSE) exec app php artisan test

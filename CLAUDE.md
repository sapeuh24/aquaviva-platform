# AQUAVIVA SAS PLATFORM — Contexto del Proyecto

## Descripcion General

Plataforma tecnologica de gestion ambiental para Aquaviva SAS. Permite gestionar proyectos ambientales, monitoreo de indicadores, reportes regulatorios, evidencias, usuarios y roles.

## Stack Tecnologico (DEFINITIVO)

### Backend
- **Framework**: Laravel 11 (PHP 8.2+)
- **API**: REST API (JSON)
- **Auth**: Laravel Sanctum (SPA tokens) + JWT fallback
- **DB**: MySQL 8.x
- **Permisos**: spatie/laravel-permission

### Frontend
- **Framework**: Vue 3 (Composition API)
- **Build**: Vite
- **CSS**: Tailwind CSS v3
- **Componentes UI**: PrimeVue 4 (tema custom corporativo)
- **Estado global**: Pinia
- **Router**: Vue Router 4
- **HTTP**: Axios

### Infraestructura
- **Proveedor**: Hostinger (shared hosting Linux)
- **Panel**: hPanel (propio de Hostinger — NO cPanel)
- **Servidor web**: LiteSpeed (compatible con .htaccess de Apache)
- **PHP disponible**: 8.2+ (seleccionable en hPanel)
- **MySQL**: 8.x (gestionado via phpMyAdmin en hPanel)
- **SSH**: Disponible en Hostinger (usar para composer install, artisan migrate)
- **SSL**: Let's Encrypt gratis via hPanel — OBLIGATORIO en produccion
- **Deploy backend**: Subcarpeta fuera de `public_html/` → subdominio `api.aquaviva.com.co` apunta a `backend/public/`
- **Deploy frontend**: Build de Vue (`dist/`) se sube a `public_html/` (dominio raiz o `app.` subdominio)
- **Subdominios**: Crear en hPanel → Dominios → Subdominios

## Estructura del Proyecto

```
aquaviva-platform/
├── .claude/agents/          # Agentes especializados de Claude
├── backend/                 # Laravel 11 API REST
├── frontend/                # Vue 3 SPA
├── database/                # Esquemas, diagramas, migraciones canonicas
└── docs/                    # Documentacion del proyecto
```

## Principios de Desarrollo

1. **API-first**: El backend expone endpoints REST puros, sin vistas Blade
2. **Separacion clara**: Frontend y backend son proyectos independientes
3. **MySQL compatible**: Todas las queries deben ser compatibles con MySQL 8
4. **Hosting compartido**: No usar features que requieran servidor Node.js o Docker en produccion
5. **Codigo limpio**: PSR-12 en PHP, ESLint + Prettier en JS/Vue
6. **Seguridad**: OWASP Top 10 siempre en mente
7. **Nomenclatura**: snake_case en DB, camelCase en JS, PascalCase en componentes Vue

## Dominio de Negocio

### Entidades principales
- **Company**: Empresa cliente que usa la plataforma
- **User**: Usuario del sistema (pertenece a una Company)
- **Program**: Programa ambiental (ej: Manejo de Residuos, Agua)
- **Project**: Proyecto especifico dentro de un Programa
- **Monitoring**: Ficha de monitoreo ambiental (PMA)
- **Worksheet**: Ficha de trabajo por herramienta/medio
- **Indicator**: Indicador de cumplimiento
- **Activity**: Actividad dentro de un indicador
- **Evidence**: Evidencia (archivo) de una actividad
- **Alert**: Alertas de vencimiento o incumplimiento

### Roles del sistema
- `super_admin`: Administrador de la plataforma (Aquaviva SAS)
- `admin`: Administrador de empresa cliente
- `coordinator`: Coordinador ambiental
- `analyst`: Analista ambiental
- `viewer`: Solo lectura

## Agentes Disponibles

Cada agente esta definido en `.claude/agents/`. Invocarlos cuando se necesite su experticia:

| Agente | Archivo | Cuando usarlo |
|--------|---------|---------------|
| Project Manager | `project-manager.md` | Planificacion, roadmap, priorizacion |
| Product Owner | `product-owner.md` | Requerimientos, historias de usuario |
| UX/UI Designer | `ux-ui-designer.md` | Diseno de interfaces, design system |
| Frontend Developer | `frontend-developer.md` | Componentes Vue, integracion API |
| Backend Developer | `backend-developer.md` | Endpoints Laravel, logica de negocio |
| Database Engineer | `database-engineer.md` | Esquema DB, optimizacion de queries |
| DevOps Engineer | `devops-engineer.md` | Deploy Apache, CI/CD, configuracion |
| QA Engineer | `qa-engineer.md` | Testing, casos de prueba, validacion |
| Data Analyst | `data-analyst.md` | KPIs, dashboards, metricas |
| Security Specialist | `security-specialist.md` | Seguridad, autenticacion, OWASP |

## Decisiones de Producto Confirmadas

- **Idioma**: Espanol unicamente (mercado latinoamericano)
- **Multi-empresa**: Soporte desde el MVP — toda la arquitectura es multi-tenant desde el dia 1
- **Mercado**: Latino — terminologia ambiental colombiana (PMA, CAR, ANLA, etc.)
- **Identidad visual**: Verde natural sobrio — paleta definida en el agente UX/UI Designer

## Notas Criticas

- El codigo anterior (aquaviva_back_new) es Laravel 6 / PHP 7.1 — NO reutilizar directamente
- La DB anterior tenia problemas de diseno: nombres inconsistentes, typos (enviorenmental), falta de indices, enums mal usados
- Hostinger usa LiteSpeed pero es 100% compatible con directivas .htaccess de Apache
- Hostinger SI tiene SSH — usarlo para composer install y php artisan (no hay que hacerlo manual via FTP)
- NO usar Docker, PM2, ni Node como servicio en produccion
- Usar `utf8mb4` (NO `utf8`) en MySQL para soporte completo de Unicode
- QUEUE_CONNECTION=sync en produccion (Hostinger shared no tiene Supervisor/Redis)
- Multitenancy es critico: NUNCA devolver datos de empresa A a usuario de empresa B

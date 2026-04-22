# Aquaviva Platform — Esquema de Base de Datos

**Version:** 1.1.0
**Fecha:** 2024-01-06
**Motor:** MySQL 8.x
**Charset global:** utf8mb4 / utf8mb4_unicode_ci

---

## Cambios v1.1 — Reunion cliente 2024-01-06

### Resumen ejecutivo

Se incorporan los requerimientos de la reunion con el cliente. Los cambios de base de datos afectan 5 areas:
1. Nuevo concepto de dominio: `organization_projects` (proyectos de la organizacion)
2. Nuevo concepto de dominio: `obligations` (obligaciones ambientales) con relacion M:N a organization_projects
3. Nuevo soporte para organigrama: `org_chart_positions`
4. Expansion del campo CIIU en empresas (codigo + descripcion, ambos texto libre)
5. Normalizacion de codigos DANE en departamentos y municipios
6. Conversion de campos FK de catalogo a texto libre en worksheets

### Tabla de cambios

| Tipo | Tabla/Campo | Descripcion del cambio | Punto reunion | Migracion |
|------|-------------|------------------------|---------------|-----------|
| NUEVO | `organization_projects` | Nueva tabla: proyectos internos de la empresa cliente | 1, 12 | `2024_01_02_000001` |
| NUEVO | `obligations` | Nueva tabla: obligaciones ambientales derivadas de licencias/permisos | 2, 13 | `2024_01_02_000002` |
| NUEVO | `obligation_organization_project` | Pivot M:N entre obligations y organization_projects | 2 | `2024_01_02_000003` |
| NUEVO | `org_chart_positions` | Nueva tabla: arbol jerarquico del organigrama por empresa | 4 | `2024_01_02_000004` |
| MODIFICADO | `companies.ciiu_code` | Expandido de VARCHAR(10) a VARCHAR(20) — texto alfanumerico libre | 7 | `2024_01_01_000009` (modificado) |
| NUEVO | `companies.ciiu_description` | Nuevo campo VARCHAR(255) para descripcion libre de actividad CIIU | 7 | `2024_01_01_000009` (modificado) |
| MODIFICADO | `departments.code` | Renombrado a `dane_code` VARCHAR(2) — codigo DANE oficial 2 digitos | 8 | `2024_01_01_000003` (modificado) |
| MODIFICADO | `municipalities.code` | Renombrado a `dane_code` VARCHAR(5) — codigo DANE oficial 5 digitos | 8 | `2024_01_01_000004` (modificado) |
| NUEVO | `municipalities.dane_department_code` | Campo redundante VARCHAR(2) para busquedas rapidas sin JOIN | 8 | `2024_01_01_000004` (modificado) |
| MODIFICADO | `worksheets.monitoring_tool_id` | Convertido de FK (→ monitoring_tools) a texto libre `monitoring_tool VARCHAR(200)` | 13 | `2024_01_01_000016` (modificado) |
| MODIFICADO | `worksheets.monitoring_phase_id` | Convertido de FK (→ monitoring_phases) a texto libre `monitoring_phase VARCHAR(100)` | 13 | `2024_01_01_000016` (modificado) |
| NUEVO | `worksheets.obligation_id` | FK nullable a obligations — nuevo flujo principal v1.1 | 2, 13 | `2024_01_01_000016` (modificado) + `2024_01_02_000005` |
| MODIFICADO | `worksheets.monitoring_id` | Cambiado de NOT NULL a NULLABLE — flujo secundario (trazabilidad) | 2 | `2024_01_01_000016` (modificado) |
| SIN CAMBIO | `evidences.uploaded_by` | Ya existia como FK a users.id — cumple Punto 15 | 15 | N/A |
| SIN CAMBIO | Sedes/branches | No existia tabla de sedes en el nuevo esquema | 5 | N/A |
| SIN CAMBIO | Envio de correo bienvenida | Logica de aplicacion, no requiere cambio DB | 6 | N/A |
| SIN CAMBIO | Filtros y sorts en tablas | Logica de frontend, no requiere cambio DB | 10, 11 | N/A |
| SIN CAMBIO | Cargue de evidencias | Logica de aplicacion, no requiere cambio DB | 9 | N/A |

### Decisiones de diseno tomadas

**Punto 1+12 — organization_projects vs monitorings:**
Se opta por la opcion **(b) crear nueva tabla `organization_projects`** y mantener `monitorings`.
- `organization_projects`: proyectos internos de la empresa cliente (ej: "Construccion Planta Norte")
- `monitorings`: fichas PMA tecnicas con numero de resolucion y autoridad ambiental — se conservan para trazabilidad regulatoria
- La jerarquia del flujo principal cambia: `companies → organization_projects ←M:N→ obligations → worksheets`
- La jerarquia secundaria se conserva: `companies → programs → projects → monitorings → worksheets`

**Punto 2 — obligations:**
Se opta por la opcion **(c) crear nueva tabla `obligations`** como concepto independiente.
- `obligations` representa requisitos especificos de licencias/permisos ambientales colombianos
- No reemplaza `worksheets` — las worksheets siguen siendo fichas de trabajo que desagregan la obligacion
- La relacion M:N con `organization_projects` refleja que una obligacion puede aplicar a varios proyectos y viceversa
- La FK `obligation_id` en `worksheets` es el nuevo punto de entrada principal

**Punto 4 — Organigrama:**
Se crea tabla `org_chart_positions` con modelo Adjacency List (parent_id self-referential).
- El problema reportado ("no funciona al agregar usuario nuevo") era dependencia de `id_area` obligatorio en el sistema anterior
- Con `department_name` como texto libre el problema queda resuelto estructuralmente
- MySQL 8 soporta CTEs recursivas para cargar el arbol completo en una sola query

**Punto 5 — Sedes:**
No existe tabla de sedes en el nuevo esquema. El campo `address` en `companies` es texto libre etiquetado como "sede principal" en su comentario. No requiere cambio DB.

**Punto 8 — DANE codes:**
- `departments.code` renombrado a `dane_code` VARCHAR(2): codigos de 2 digitos del DANE (05=Antioquia, 11=Bogota DC, etc.)
- `municipalities.code` renombrado a `dane_code` VARCHAR(5): codigos de 5 digitos (2 dept + 3 municipio)
- Se agrega `municipalities.dane_department_code` VARCHAR(2) como campo redundante para evitar JOINs en busquedas de municipios por departamento

**Punto 13 — Campos texto libre en worksheets:**
Los campos `monitoring_tool_id` y `monitoring_phase_id` (FKs a catalogos de dropdowns) se convierten a VARCHAR texto libre. Las tablas `monitoring_tools` y `monitoring_phases` se conservan como catalogos opcionales que el backend puede usar para sugerencias/autocomplete, pero ya no son obligatorias en worksheets.

**Punto 15 — uploaded_by en evidences:**
El campo `uploaded_by` (FK a `users.id`) ya existia en el esquema original. La logica de "solo el usuario que subio puede eliminar" se implementa en la capa de aplicacion (Laravel Policy), no requiere cambio DB.

---

## Indice

1. [Principios de Diseno](#1-principios-de-diseno)
2. [Decisiones vs Sistema Anterior](#2-decisiones-vs-sistema-anterior)
3. [Catalogo de Tablas](#3-catalogo-de-tablas)
4. [Definicion Detallada de Tablas](#4-definicion-detallada-de-tablas)
5. [Diagrama de Relaciones ERD](#5-diagrama-de-relaciones-erd)
6. [Estimacion de Volumetria](#6-estimacion-de-volumetria)
7. [Indices y Estrategia de Busqueda](#7-indices-y-estrategia-de-busqueda)

---

## 1. Principios de Diseno

- **Multi-tenant desde el inicio**: Toda entidad de negocio tiene `company_id` o esta relacionada transitivamente con `companies`.
- **Soft deletes obligatorios** en tablas de negocio: datos ambientales nunca se borran fisicamente (trazabilidad regulatoria).
- **Sin ENUMs en DB**: Los estados/tipos se validan en la capa de aplicacion (Laravel Rules). La DB almacena strings cortos.
- **FK con ON DELETE RESTRICT** por defecto. CASCADE solo donde la eliminacion logica del padre implica inexistencia del hijo.
- **Charset utf8mb4**: Soporte completo de Unicode incluyendo emojis y caracteres especiales del espanol.
- **Indices en toda FK** y columnas de busqueda/ordenamiento frecuente.
- **Nomenclatura consistente**: tablas en plural snake_case, FK en formato `{tabla_singular}_id`.

---

## 2. Decisiones vs Sistema Anterior

| Problema anterior | Solucion nueva | Justificacion |
|---|---|---|
| Tabla `enviorenmental_monitoring` (typo) | Tabla `monitorings` | Nombre correcto, plural, sin typo |
| Tabla `tool` (singular) | Tabla `monitoring_tools` | Plural, nombre descriptivo |
| Tabla `company` (singular) | Tabla `companies` | Convension plural |
| Tabla `project` (singular) | Tabla `projects` | Convension plural |
| Tabla `worksheet` (singular) | Tabla `worksheets` | Convension plural |
| Tabla `area` (singular, vaga) | Eliminada | Concepto de "area" organizacional no es parte del dominio ambiental del MVP |
| Tabla `structure` (organigrama) | Eliminada | Fuera del alcance del MVP; roles/permisos se manejan con spatie |
| Tabla `addresses` como FK en users | Eliminada; `company_id` + `municipality_id` directo | Innecesariamente complejo; los datos de ubicacion van en `companies` |
| FK nombradas `id_tool`, `id_company` | FK nombradas `tool_id`, `company_id` | Convencion Laravel estandar |
| `utf8` + `utf8_unicode_ci` | `utf8mb4` + `utf8mb4_unicode_ci` | Soporte completo Unicode, requerido para MySQL 8 |
| Sin soft deletes | `deleted_at` en tablas de negocio | Trazabilidad regulatoria ambiental |
| Sin indices en FK | Indices en todas las FK | Performance en JOINs |
| ENUM en `programs.medium` | FK a `environmental_media` | Extensible sin migracion; catalogo editable |
| ENUM en `worksheets.phase` | FK a `monitoring_phases` | Extensible sin migracion |
| `activities` FK a `worksheet` (no a `indicator`) | `activities` FK a `indicators` | Correcto segun modelo de dominio: actividades ejecutan indicadores |
| `evidences` sin campos de archivo | `evidences` con `original_name`, `storage_path`, `mime_type`, `size_bytes` | Gestion real de archivos |
| `alerts` con `id_obligation` (sin FK real) | `alerts` con `indicator_id` + `type` | Relacion correcta al dominio |
| Tabla `ciiu` propia | Campo `ciiu_code VARCHAR(10)` en `companies` | CIIU es catalogo estandar externo; mantenerlo en DB propia es sobrediseno |
| `responsibles` como tabla separada | Tabla pivot `activity_user` | Semantica mas clara para relacion M:N actividad-responsable |
| Tabla `currencies` standalone | Eliminada del MVP | Solo se usaba en `activities`; se puede reintroducir cuando haya modulo financiero |
| Tabla `formats`/`components`/`excels` | Eliminadas del MVP | Eran funcionalidad de exportacion no completada; se redisena cuando sea necesario |
| Sin `company_id` en tablas principales | `company_id` en `programs`, `projects`, etc. | Multi-tenancy correcto desde el inicio |

---

## 3. Catalogo de Tablas

### Tablas de Catalogo (datos de referencia, sin soft deletes)

| # | Tabla | Proposito | Filas estimadas |
|---|---|---|---|
| 1 | `document_types` | Tipos de documento (CC, NIT, Pasaporte) | ~10 |
| 2 | `environmental_authorities` | Autoridades ambientales (ANLA, CAR, CVC...) | ~50 |
| 3 | `departments` | Departamentos de Colombia | 33 |
| 4 | `municipalities` | Municipios de Colombia | ~1.122 |
| 5 | `environmental_media` | Medios ambientales (Abiotico, Biotico, Socioeconomico) | ~5 |
| 6 | `monitoring_phases` | Fases de monitoreo (Constructiva, Operativa...) | ~5 |
| 7 | `indicator_frequencies` | Frecuencias de medicion (Mensual, Trimestral...) | ~8 |
| 8 | `monitoring_tools` | Instrumentos de monitoreo (Ficha, Formato, Encuesta...) | ~20 |

### Tablas Principales (con soft deletes, multi-tenant)

| # | Tabla | Proposito | Filas est. 1 ano |
|---|---|---|---|
| 9 | `companies` | Empresas cliente de Aquaviva | ~50 |
| 10 | `users` | Usuarios del sistema | ~500 |
| 11 | `programs` | Programas ambientales por empresa | ~500 |
| 12 | `projects` | Proyectos dentro de programas | ~1.000 |
| 13 | `monitorings` | Fichas PMA (ficha de monitoreo ambiental) | ~2.000 |
| 14 | `worksheets` | Fichas de trabajo (instrumento + fase) | ~10.000 |
| 15 | `indicators` | Indicadores de cumplimiento | ~50.000 |
| 16 | `activities` | Actividades de cada indicador | ~200.000 |
| 17 | `evidences` | Archivos de evidencia de actividades | ~500.000 |
| 18 | `alerts` | Alertas de vencimiento/incumplimiento | ~100.000 |
| 23 | `organization_projects` | **v1.1** Proyectos internos de la empresa cliente | ~500 |
| 24 | `obligations` | **v1.1** Obligaciones ambientales (licencias, permisos) | ~5.000 |
| 26 | `org_chart_positions` | **v1.1** Nodos del organigrama por empresa | ~1.000 |

### Tablas de Auditoria / Control

| # | Tabla | Proposito | Filas est. 1 ano |
|---|---|---|---|
| 19 | `indicator_controls` | Historico de cumplimiento de indicadores | ~150.000 |

### Tablas Pivot (relaciones M:N)

| # | Tabla | Relacion | Filas est. 1 ano |
|---|---|---|---|
| 20 | `company_user` | Usuario pertenece a multiples empresas (super_admin) | ~600 |
| 21 | `project_user` | Responsables asignados a un proyecto | ~3.000 |
| 22 | `activity_user` | Responsables de una actividad | ~400.000 |
| 25 | `obligation_organization_project` | **v1.1** Obligacion ↔ Proyecto de la organizacion (M:N) | ~10.000 |

---

## 4. Definicion Detallada de Tablas

---

### 4.1 `document_types`

**Proposito:** Catalogo de tipos de documento de identidad.

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `name` | VARCHAR(100) | NOT NULL | Nombre del tipo (Cedula de Ciudadania, NIT...) |
| `code` | VARCHAR(10) | NOT NULL UNIQUE | Codigo corto (CC, NIT, CE, PA...) |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**Indices:** PK(id), UNIQUE(code)

---

### 4.2 `environmental_authorities`

**Proposito:** Catalogo de autoridades ambientales colombianas (ANLA, CARs regionales, etc.).

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `name` | VARCHAR(200) | NOT NULL | Nombre completo (ANLA, CAR Cundinamarca...) |
| `acronym` | VARCHAR(30) | NOT NULL UNIQUE | Sigla (ANLA, CAR, CVC, CORPOCALDAS...) |
| `jurisdiction` | VARCHAR(200) | NULL | Descripcion de jurisdiccion geografica |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**Indices:** PK(id), UNIQUE(acronym), IDX(name)

---

### 4.3 `departments`

**Proposito:** Departamentos de Colombia (33 departamentos + Bogota D.C.).

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `name` | VARCHAR(100) | NOT NULL | Nombre del departamento |
| `dane_code` | VARCHAR(2) | NOT NULL UNIQUE | Codigo DANE oficial del departamento (2 digitos: 05=Antioquia, 11=Bogota DC) — v1.1: antes `code VARCHAR(5)` |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**Indices:** PK(id), UNIQUE(dane_code)

---

### 4.4 `municipalities`

**Proposito:** Municipios de Colombia (~1.122), agrupados por departamento.

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `department_id` | BIGINT UNSIGNED | NOT NULL FK | Departamento al que pertenece |
| `name` | VARCHAR(150) | NOT NULL | Nombre del municipio |
| `dane_code` | VARCHAR(5) | NOT NULL UNIQUE | Codigo DANE oficial del municipio (5 digitos: 2 dept + 3 municipio, ej: 05001=Medellin) — v1.1: antes `code VARCHAR(8)` |
| `dane_department_code` | VARCHAR(2) | NULL | Codigo DANE del departamento (redundante para busquedas rapidas sin JOIN) — v1.1: campo nuevo |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**FK:** `department_id` → `departments.id` ON DELETE RESTRICT
**Indices:** PK(id), UNIQUE(dane_code), IDX(department_id), IDX(name), IDX(dane_department_code)

---

### 4.5 `environmental_media`

**Proposito:** Catalogo de medios ambientales (Abiotico, Biotico, Socioeconomico). Reemplaza el ENUM de `programs.medium` del sistema anterior.

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `name` | VARCHAR(100) | NOT NULL UNIQUE | Nombre (Abiotico, Biotico, Socioeconomico) |
| `description` | TEXT | NULL | Descripcion del medio ambiental |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**Indices:** PK(id), UNIQUE(name)

---

### 4.6 `monitoring_phases`

**Proposito:** Fases del ciclo de vida del proyecto ambiental. Reemplaza el ENUM de `worksheet.phase`.

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `name` | VARCHAR(100) | NOT NULL UNIQUE | Nombre (Constructiva, Operativa, Desmantelamiento y abandono) |
| `order` | TINYINT UNSIGNED | NOT NULL DEFAULT 0 | Orden de presentacion |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**Indices:** PK(id), UNIQUE(name)

---

### 4.7 `indicator_frequencies`

**Proposito:** Frecuencias de medicion de indicadores. Reemplaza el campo string libre `frequency` en `indicators`.

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `name` | VARCHAR(100) | NOT NULL UNIQUE | Nombre (Mensual, Bimestral, Trimestral, Semestral, Anual, Puntual) |
| `months_interval` | TINYINT UNSIGNED | NULL | Intervalo en meses (1, 2, 3, 6, 12, NULL para puntual) |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**Indices:** PK(id), UNIQUE(name)

---

### 4.8 `monitoring_tools`

**Proposito:** Instrumentos o herramientas de monitoreo utilizados en las fichas de trabajo. Antes era la tabla `tool` (singular, sin descripcion, sin charset correcto).

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `name` | VARCHAR(200) | NOT NULL UNIQUE | Nombre de la herramienta (Ficha de campo, Formato de seguimiento...) |
| `description` | TEXT | NULL | Descripcion del instrumento |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**Indices:** PK(id), UNIQUE(name)

---

### 4.9 `companies`

**Proposito:** Empresas cliente de la plataforma. Es la raiz del multi-tenancy.

**Cambios vs sistema anterior:**
- Nombre plural `companies` (antes `company`)
- FK `municipality_id` directa (antes pasaba por `addresses` — innecesariamente complejo)
- `ciiu_code` como VARCHAR en lugar de FK a tabla `ciiu` (codigo estandar externo)
- Logo con path real (`VARCHAR(500)`)
- Soft deletes agregados
- `phone` como VARCHAR (antes BIGINT — error de tipo para telefonos)

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `name` | VARCHAR(200) | NOT NULL | Razon social |
| `nit` | VARCHAR(20) | NOT NULL UNIQUE | NIT con digito verificacion (ej: 900123456-1) |
| `ciiu_code` | VARCHAR(20) | NULL | Codigo CIIU — texto alfanumerico libre (v1.1: expandido de VARCHAR(10)) |
| `ciiu_description` | VARCHAR(255) | NULL | Descripcion libre de la actividad economica CIIU (v1.1: campo nuevo) |
| `municipality_id` | BIGINT UNSIGNED | NULL FK | Municipio sede principal |
| `address` | VARCHAR(300) | NULL | Direccion sede principal |
| `phone` | VARCHAR(20) | NULL | Telefono de contacto |
| `email` | VARCHAR(150) | NULL | Email de contacto corporativo |
| `logo_path` | VARCHAR(500) | NULL | Path del logo almacenado |
| `is_active` | TINYINT(1) | NOT NULL DEFAULT 1 | Estado de la empresa en la plataforma |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:** `municipality_id` → `municipalities.id` ON DELETE SET NULL
**Indices:** PK(id), UNIQUE(nit), IDX(municipality_id), IDX(is_active)

---

### 4.10 `users`

**Proposito:** Usuarios del sistema. Un usuario pertenece a una empresa principal (`company_id`) pero puede acceder a multiples via `company_user`.

**Cambios vs sistema anterior:**
- `company_id` directo (antes era via `addresses`)
- `document_type_id` (antes `id_document_type` — patron incorrecto)
- `mobile` como VARCHAR (antes BIGINT — error de tipo)
- `email_verified_at` agregado (requerido por Laravel Sanctum)
- Sin FK a `addresses` (innecesario)
- Soft deletes

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa principal a la que pertenece |
| `document_type_id` | BIGINT UNSIGNED | NOT NULL FK | Tipo de documento |
| `document_number` | VARCHAR(30) | NOT NULL | Numero de documento |
| `first_name` | VARCHAR(100) | NOT NULL | Nombres |
| `last_name` | VARCHAR(100) | NOT NULL | Apellidos |
| `email` | VARCHAR(150) | NOT NULL UNIQUE | Correo electronico (login) |
| `email_verified_at` | TIMESTAMP | NULL | Verificacion de email |
| `password` | VARCHAR(255) | NOT NULL | Hash de password |
| `phone` | VARCHAR(20) | NULL | Telefono movil |
| `is_active` | TINYINT(1) | NOT NULL DEFAULT 1 | Estado del usuario |
| `remember_token` | VARCHAR(100) | NULL | Token "recordarme" |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `document_type_id` → `document_types.id` ON DELETE RESTRICT

**Indices:** PK(id), UNIQUE(email), IDX(company_id), IDX(document_type_id), IDX(document_number), IDX(is_active)

**Nota:** Roles y permisos se manejan via `spatie/laravel-permission` (tablas `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`).

---

### 4.11 `programs`

**Proposito:** Programa ambiental. Agrupa proyectos bajo un objetivo comun (ej: "Programa de Manejo de Agua").

**Cambios vs sistema anterior:**
- `company_id` en lugar de `id_company`
- `environmental_medium_id` FK a catalogo (antes ENUM hardcodeado)
- Soft deletes

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa duena del programa |
| `environmental_medium_id` | BIGINT UNSIGNED | NOT NULL FK | Medio ambiental del programa |
| `name` | VARCHAR(200) | NOT NULL | Nombre del programa |
| `code` | VARCHAR(50) | NOT NULL | Codigo interno del programa |
| `description` | TEXT | NULL | Descripcion y objetivos del programa |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `environmental_medium_id` → `environmental_media.id` ON DELETE RESTRICT

**Indices:** PK(id), IDX(company_id), IDX(environmental_medium_id), IDX(code)
**Unique:** (company_id, code) — codigo unico por empresa

---

### 4.12 `projects`

**Proposito:** Proyecto especifico dentro de un programa ambiental.

**Cambios vs sistema anterior:**
- Nombre plural `projects` (antes `project`)
- `company_id` directo para multi-tenancy eficiente
- Campos enriquecidos (descripcion, fechas, estado)
- Soft deletes

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa (desnormalizacion para multi-tenancy) |
| `program_id` | BIGINT UNSIGNED | NOT NULL FK | Programa al que pertenece |
| `name` | VARCHAR(200) | NOT NULL | Nombre del proyecto |
| `code` | VARCHAR(50) | NULL | Codigo interno |
| `description` | TEXT | NULL | Descripcion del proyecto |
| `start_date` | DATE | NULL | Fecha de inicio |
| `end_date` | DATE | NULL | Fecha de cierre prevista |
| `status` | VARCHAR(30) | NOT NULL DEFAULT 'activo' | Estado: activo, pausado, cerrado |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `program_id` → `programs.id` ON DELETE RESTRICT

**Indices:** PK(id), IDX(company_id), IDX(program_id), IDX(status)

---

### 4.13 `monitorings`

**Proposito:** Ficha de Monitoreo Ambiental (Ficha PMA). Antes llamada `enviorenmental_monitoring` (typo) con campo `especification` (typo) y relacion incorrecta via `addresses` en lugar de `municipalities`.

**Cambios vs sistema anterior:**
- Nombre correcto `monitorings` (plural, sin typo)
- `project_id` como FK correcta (antes tenia campo `project` como string — error grave)
- `municipality_id` directo (antes via `id_town` con FK correcta pero tabla llamada `towns`)
- `environmental_authority_id` (antes `id_authority`)
- Campo `specification` correcto (antes `especification` con typo)
- `company_id` para multi-tenancy
- Eliminada FK a `addresses` (innecesario)
- Soft deletes

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa (desnormalizacion para multi-tenancy) |
| `project_id` | BIGINT UNSIGNED | NOT NULL FK | Proyecto al que pertenece la ficha |
| `municipality_id` | BIGINT UNSIGNED | NULL FK | Municipio donde se realiza el monitoreo |
| `environmental_authority_id` | BIGINT UNSIGNED | NULL FK | Autoridad ambiental competente |
| `name` | VARCHAR(200) | NOT NULL | Nombre de la ficha PMA |
| `specification` | TEXT | NULL | Especificaciones tecnicas del monitoreo |
| `resolution_number` | VARCHAR(100) | NULL | Numero de resolucion ambiental |
| `resolution_date` | DATE | NULL | Fecha de la resolucion |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `project_id` → `projects.id` ON DELETE RESTRICT
- `municipality_id` → `municipalities.id` ON DELETE SET NULL
- `environmental_authority_id` → `environmental_authorities.id` ON DELETE SET NULL

**Indices:** PK(id), IDX(company_id), IDX(project_id), IDX(municipality_id), IDX(environmental_authority_id)

---

### 4.14 `worksheets`

**Proposito:** Ficha de trabajo que desagrega una obligacion ambiental en aspectos evaluables. Antes asociada directamente a la ficha PMA (monitoring) y a catalogos de herramienta/fase.

**Cambios vs sistema anterior:**
- Nombre plural `worksheets`
- v1.1: `obligation_id` FK nullable a `obligations` — nuevo flujo principal (Punto 2)
- v1.1: `monitoring_id` pasa a NULLABLE — flujo secundario de trazabilidad (antes NOT NULL)
- v1.1: `monitoring_tool_id` y `monitoring_phase_id` eliminados como FK; reemplazados por campos de texto libre `monitoring_tool` y `monitoring_phase` (Punto 13)
- `company_id` para multi-tenancy
- Soft deletes

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa (desnormalizacion para multi-tenancy) |
| `obligation_id` | BIGINT UNSIGNED | NULL FK | Obligacion ambiental — nuevo flujo principal v1.1 |
| `monitoring_id` | BIGINT UNSIGNED | NULL FK | Ficha PMA — flujo secundario de trazabilidad regulatoria |
| `monitoring_tool` | VARCHAR(200) | NULL | Herramienta o instrumento — texto libre (v1.1: antes FK a monitoring_tools) |
| `monitoring_phase` | VARCHAR(100) | NULL | Fase del proyecto — texto libre (v1.1: antes FK a monitoring_phases) |
| `name` | VARCHAR(200) | NULL | Nombre descriptivo de la ficha de trabajo |
| `objective` | TEXT | NULL | Objetivo de la ficha |
| `target` | TEXT | NULL | Meta a alcanzar |
| `observations` | TEXT | NULL | Observaciones generales |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `obligation_id` → `obligations.id` ON DELETE SET NULL (agr. via migracion `2024_01_02_000005`)
- `monitoring_id` → `monitorings.id` ON DELETE SET NULL

**Indices:** PK(id), IDX(company_id), IDX(obligation_id), IDX(monitoring_id)

**Nota:** Las tablas `monitoring_tools` y `monitoring_phases` se conservan como catalogos para autocomplete en el frontend, pero ya no tienen FK obligatoria en worksheets.

---

### 4.15 `indicators`

**Proposito:** Indicadores de cumplimiento ambiental asociados a una ficha de trabajo.

**Cambios vs sistema anterior:**
- `worksheet_id` (antes `id_worksheet`)
- `indicator_frequency_id` FK a catalogo (antes string libre `frequency`)
- `company_id` para multi-tenancy
- Soft deletes

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa (desnormalizacion para multi-tenancy) |
| `worksheet_id` | BIGINT UNSIGNED | NOT NULL FK | Ficha de trabajo a la que pertenece |
| `indicator_frequency_id` | BIGINT UNSIGNED | NULL FK | Frecuencia de medicion |
| `name` | TEXT | NOT NULL | Nombre/descripcion del indicador |
| `objective` | TEXT | NULL | Objetivo del indicador |
| `target` | TEXT | NULL | Meta o valor esperado |
| `measurement_unit` | VARCHAR(100) | NULL | Unidad de medida (%, m3, ton, etc.) |
| `baseline_value` | DECIMAL(15,4) | NULL | Valor de linea base |
| `target_value` | DECIMAL(15,4) | NULL | Valor meta numerico |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `worksheet_id` → `worksheets.id` ON DELETE RESTRICT
- `indicator_frequency_id` → `indicator_frequencies.id` ON DELETE SET NULL

**Indices:** PK(id), IDX(company_id), IDX(worksheet_id), IDX(indicator_frequency_id)

---

### 4.16 `activities`

**Proposito:** Actividades especificas que ejecutan un indicador. Cada actividad tiene fecha programada, fecha ejecutada y estado.

**Cambios vs sistema anterior:**
- `indicator_id` como FK (antes `id_worksheet` — error de dominio: las actividades pertenecen a indicadores, no a fichas directamente)
- `scheduled_date` / `executed_date` (antes `initial_date` / `final_date` — semantica mas clara)
- `status` string (antes ausente)
- `company_id` para multi-tenancy
- Eliminada FK a `currencies` (modulo financiero no esta en MVP)
- Soft deletes

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa (desnormalizacion para multi-tenancy) |
| `indicator_id` | BIGINT UNSIGNED | NOT NULL FK | Indicador al que pertenece |
| `name` | TEXT | NOT NULL | Descripcion de la actividad |
| `scheduled_date` | DATE | NULL | Fecha programada de ejecucion |
| `executed_date` | DATE | NULL | Fecha real de ejecucion |
| `status` | VARCHAR(30) | NOT NULL DEFAULT 'pendiente' | Estado: pendiente, en_ejecucion, cumplida, no_cumplida, parcial |
| `observations` | TEXT | NULL | Observaciones de la actividad |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `indicator_id` → `indicators.id` ON DELETE RESTRICT

**Indices:** PK(id), IDX(company_id), IDX(indicator_id), IDX(status), IDX(scheduled_date), IDX(executed_date)

---

### 4.17 `evidences`

**Proposito:** Archivos de evidencia asociados a actividades. Antes la tabla `evidences` tenia solo `compliance` e `observations` sin campos de archivo real — era un diseno incompleto.

**Cambios vs sistema anterior:**
- Campos reales de archivo: `original_name`, `storage_path`, `mime_type`, `size_bytes`
- `activity_id` (antes `id_activity`)
- `company_id` para multi-tenancy
- Soft deletes

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa (desnormalizacion para multi-tenancy) |
| `activity_id` | BIGINT UNSIGNED | NOT NULL FK | Actividad a la que pertenece la evidencia |
| `original_name` | VARCHAR(255) | NOT NULL | Nombre original del archivo subido |
| `storage_path` | VARCHAR(500) | NOT NULL | Path en el storage de la aplicacion |
| `mime_type` | VARCHAR(100) | NOT NULL | Tipo MIME del archivo |
| `size_bytes` | BIGINT UNSIGNED | NOT NULL | Tamano del archivo en bytes |
| `description` | TEXT | NULL | Descripcion o comentario sobre la evidencia |
| `uploaded_by` | BIGINT UNSIGNED | NULL FK | Usuario que subio el archivo |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `activity_id` → `activities.id` ON DELETE RESTRICT
- `uploaded_by` → `users.id` ON DELETE SET NULL

**Indices:** PK(id), IDX(company_id), IDX(activity_id), IDX(uploaded_by)

---

### 4.18 `alerts`

**Proposito:** Alertas automaticas de vencimiento o incumplimiento de indicadores. Antes relacionada solo con usuarios y con `id_obligation` sin FK real.

**Cambios vs sistema anterior:**
- `indicator_id` FK correcta al dominio (antes `id_obligation` sin FK definida)
- `type` campo de tipo de alerta (vencimiento, incumplimiento, etc.)
- `status` para gestionar el ciclo de vida de la alerta
- `due_date` (antes `date` — nombre ambiguo)
- `company_id` para multi-tenancy
- Soft deletes

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa (desnormalizacion para multi-tenancy) |
| `indicator_id` | BIGINT UNSIGNED | NOT NULL FK | Indicador que genera la alerta |
| `user_id` | BIGINT UNSIGNED | NULL FK | Usuario notificado (NULL = todos los responsables) |
| `type` | VARCHAR(50) | NOT NULL | Tipo: vencimiento, incumplimiento, recordatorio |
| `due_date` | DATE | NOT NULL | Fecha limite o fecha del evento |
| `status` | VARCHAR(30) | NOT NULL DEFAULT 'activa' | Estado: activa, vista, resuelta, ignorada |
| `message` | TEXT | NULL | Mensaje descriptivo de la alerta |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `indicator_id` → `indicators.id` ON DELETE RESTRICT
- `user_id` → `users.id` ON DELETE SET NULL

**Indices:** PK(id), IDX(company_id), IDX(indicator_id), IDX(user_id), IDX(status), IDX(due_date)

---

### 4.19 `indicator_controls`

**Proposito:** Registro historico del nivel de cumplimiento de indicadores en cada periodo de medicion.

**Cambios vs sistema anterior:**
- Nombre plural `indicator_controls` (antes `indicator_control`)
- `indicator_id` (antes `id_indicator`)
- `company_id` para multi-tenancy
- Campos renombrados a snake_case semantico

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa (desnormalizacion para multi-tenancy) |
| `indicator_id` | BIGINT UNSIGNED | NOT NULL FK | Indicador evaluado |
| `measured_by` | BIGINT UNSIGNED | NULL FK | Usuario que registro el control |
| `period_start` | DATE | NOT NULL | Inicio del periodo medido |
| `period_end` | DATE | NOT NULL | Fin del periodo medido |
| `parameter_name` | VARCHAR(200) | NULL | Nombre del parametro evaluado |
| `parameter_value` | VARCHAR(300) | NULL | Valor del parametro |
| `feature_name` | VARCHAR(200) | NULL | Nombre de la caracteristica evaluada |
| `feature_value` | VARCHAR(300) | NULL | Valor de la caracteristica |
| `compliance_percentage` | DECIMAL(5,2) | NULL | Porcentaje de cumplimiento (0.00 a 100.00) |
| `compliance_status` | VARCHAR(30) | NOT NULL | Estado: cumplido, parcial, no_cumplido |
| `notes` | TEXT | NULL | Notas del evaluador |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `indicator_id` → `indicators.id` ON DELETE RESTRICT
- `measured_by` → `users.id` ON DELETE SET NULL

**Indices:** PK(id), IDX(company_id), IDX(indicator_id), IDX(measured_by), IDX(period_start), IDX(compliance_status)

---

### 4.20 `company_user` (pivot)

**Proposito:** Permite que un usuario (especialmente super_admin de Aquaviva) acceda a multiples empresas. Un usuario siempre tiene una empresa principal en `users.company_id`, pero puede tener acceso extendido a otras via esta tabla.

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa a la que se da acceso |
| `user_id` | BIGINT UNSIGNED | NOT NULL FK | Usuario con acceso |
| `granted_at` | TIMESTAMP | NULL | Cuando se otorgo el acceso |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**FK:**
- `company_id` → `companies.id` ON DELETE CASCADE
- `user_id` → `users.id` ON DELETE CASCADE

**Indices:** PK(id), UNIQUE(company_id, user_id), IDX(user_id)

**Nota:** ON DELETE CASCADE en ambas FK — si se elimina la empresa o el usuario, el acceso pierde sentido.

---

### 4.21 `project_user` (pivot)

**Proposito:** Responsables asignados a un proyecto. Reemplaza la tabla `responsibles` del sistema anterior que ligaba responsables a actividades (demasiado granular para la gestion de proyecto).

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `project_id` | BIGINT UNSIGNED | NOT NULL FK | Proyecto |
| `user_id` | BIGINT UNSIGNED | NOT NULL FK | Usuario responsable |
| `role_in_project` | VARCHAR(100) | NULL | Rol en el proyecto (Coordinador, Analista, etc.) |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**FK:**
- `project_id` → `projects.id` ON DELETE CASCADE
- `user_id` → `users.id` ON DELETE CASCADE

**Indices:** PK(id), UNIQUE(project_id, user_id), IDX(user_id)

---

### 4.22 `activity_user` (pivot)

**Proposito:** Responsables asignados a una actividad especifica. Equivale a la tabla `responsibles` del sistema anterior pero con convencion de nombres correcta.

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `activity_id` | BIGINT UNSIGNED | NOT NULL FK | Actividad |
| `user_id` | BIGINT UNSIGNED | NOT NULL FK | Usuario responsable |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**FK:**
- `activity_id` → `activities.id` ON DELETE CASCADE
- `user_id` → `users.id` ON DELETE CASCADE

**Indices:** PK(id), UNIQUE(activity_id, user_id), IDX(user_id)

---

### 4.23 `organization_projects` (v1.1 — nuevo)

**Proposito:** Proyectos internos de la empresa cliente (ej: "Construccion Planta Norte", "Expansion Area 3"). Son los proyectos que generan obligaciones ambientales. Concepto distinto de los `projects` (proyectos dentro de programas ambientales) y de `monitorings` (fichas PMA tecnicas).

**Origen del requerimiento:** Puntos 1 y 12 de la reunion de cliente 2024-01-06.

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa duena del proyecto |
| `name` | VARCHAR(200) | NOT NULL | Nombre del proyecto de la organizacion |
| `code` | VARCHAR(50) | NULL | Codigo interno del proyecto |
| `description` | TEXT | NULL | Descripcion del proyecto |
| `location` | VARCHAR(300) | NULL | Lugar o ubicacion del proyecto |
| `municipality_id` | BIGINT UNSIGNED | NULL FK | Municipio donde se ejecuta el proyecto |
| `start_date` | DATE | NULL | Fecha de inicio |
| `end_date` | DATE | NULL | Fecha estimada de cierre |
| `status` | VARCHAR(30) | NOT NULL DEFAULT 'activo' | Estado: activo, pausado, cerrado, finalizado |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `municipality_id` → `municipalities.id` ON DELETE SET NULL

**Indices:** PK(id), IDX(company_id), IDX(municipality_id), IDX(status)

---

### 4.24 `obligations` (v1.1 — nuevo)

**Proposito:** Obligaciones ambientales derivadas de instrumentos legales (Licencias Ambientales, Permisos de Vertimientos, Concesiones de Agua, PMAs, etc.). Son los requisitos especificos que la empresa debe cumplir ante la autoridad ambiental competente.

**Origen del requerimiento:** Puntos 2 y 13 de la reunion de cliente 2024-01-06.

**Posicion en la jerarquia:** Una obligacion puede estar asociada a muchos proyectos de la organizacion (M:N via pivot) y puede tener muchas fichas de trabajo (worksheets) que la desagregan.

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa duena de la obligacion |
| `monitoring_id` | BIGINT UNSIGNED | NULL FK | Ficha PMA de la que deriva (opcional, para trazabilidad) |
| `environmental_authority_id` | BIGINT UNSIGNED | NULL FK | Autoridad ambiental que emitio la obligacion |
| `resolution_number` | VARCHAR(100) | NULL | Numero de resolucion, permiso o acto administrativo |
| `resolution_date` | DATE | NULL | Fecha de emision del instrumento legal |
| `instrument_type` | VARCHAR(150) | NULL | Tipo: Licencia Ambiental, PMA, Permiso, etc. — texto libre |
| `name` | VARCHAR(300) | NOT NULL | Nombre/titulo de la obligacion |
| `description` | TEXT | NULL | Descripcion completa |
| `legal_basis` | TEXT | NULL | Fundamento legal o articulo del instrumento |
| `environmental_medium` | VARCHAR(150) | NULL | Medio ambiental — texto libre |
| `obligation_type` | VARCHAR(150) | NULL | Tipo de obligacion — texto libre |
| `compliance_deadline` | DATE | NULL | Fecha limite de cumplimiento |
| `compliance_frequency` | VARCHAR(100) | NULL | Frecuencia: mensual, trimestral, unica vez, etc. — texto libre |
| `status` | VARCHAR(30) | NOT NULL DEFAULT 'vigente' | Estado: vigente, cumplida, vencida, suspendida |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `monitoring_id` → `monitorings.id` ON DELETE SET NULL
- `environmental_authority_id` → `environmental_authorities.id` ON DELETE SET NULL

**Indices:** PK(id), IDX(company_id), IDX(monitoring_id), IDX(environmental_authority_id), IDX(status), IDX(compliance_deadline)

---

### 4.25 `obligation_organization_project` (v1.1 — nuevo, pivot)

**Proposito:** Tabla pivot para la relacion M:N entre obligations y organization_projects. Una obligacion puede aplicar a multiples proyectos de la organizacion; un proyecto puede tener multiples obligaciones.

**Origen del requerimiento:** Punto 2 de la reunion de cliente 2024-01-06.

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `obligation_id` | BIGINT UNSIGNED | NOT NULL FK | Obligacion ambiental |
| `organization_project_id` | BIGINT UNSIGNED | NOT NULL FK | Proyecto de la organizacion |
| `notes` | TEXT | NULL | Notas sobre la relacion especifica |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |

**FK:**
- `obligation_id` → `obligations.id` ON DELETE CASCADE
- `organization_project_id` → `organization_projects.id` ON DELETE CASCADE

**Indices:** PK(id), UNIQUE(obligation_id, organization_project_id), IDX(organization_project_id)

---

### 4.26 `org_chart_positions` (v1.1 — nuevo)

**Proposito:** Arbol jerarquico del organigrama de cada empresa cliente. Modelo Adjacency List con self-referential FK para representar la jerarquia. Resuelve el Punto 4 del cliente ("organigrama no funciona al agregar usuario nuevo").

**Origen del requerimiento:** Punto 4 de la reunion de cliente 2024-01-06.

**Raiz del problema anterior:** La tabla `structure` del sistema legacy requeria `id_area` NOT NULL (FK a tabla `area` con datos faltantes). El nuevo diseno usa `department_name` como texto libre, eliminando la dependencia.

**Query de arbol completo (MySQL 8 CTE recursiva):**
```sql
WITH RECURSIVE org_tree AS (
  SELECT id, parent_id, position_title, department_name, user_id, order, 0 AS depth
  FROM org_chart_positions WHERE company_id = ? AND parent_id IS NULL
  UNION ALL
  SELECT p.id, p.parent_id, p.position_title, p.department_name, p.user_id, p.order, t.depth + 1
  FROM org_chart_positions p INNER JOIN org_tree t ON p.parent_id = t.id
  WHERE p.deleted_at IS NULL
)
SELECT * FROM org_tree ORDER BY depth, order;
```

| Columna | Tipo | Constraint | Descripcion |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK AUTO_INCREMENT | |
| `company_id` | BIGINT UNSIGNED | NOT NULL FK | Empresa duena del organigrama |
| `parent_id` | BIGINT UNSIGNED | NULL FK (self) | Nodo padre (NULL = nodo raiz) |
| `user_id` | BIGINT UNSIGNED | NULL FK | Usuario asignado (NULL = cargo vacante) |
| `position_title` | VARCHAR(200) | NOT NULL | Titulo del cargo o posicion |
| `department_name` | VARCHAR(200) | NULL | Nombre del area/departamento — texto libre |
| `order` | SMALLINT UNSIGNED | NOT NULL DEFAULT 0 | Orden entre hermanos del mismo nivel |
| `created_at` | TIMESTAMP | NULL | |
| `updated_at` | TIMESTAMP | NULL | |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

**FK:**
- `company_id` → `companies.id` ON DELETE RESTRICT
- `parent_id` → `org_chart_positions.id` ON DELETE SET NULL (self-referential)
- `user_id` → `users.id` ON DELETE SET NULL

**Indices:** PK(id), IDX(company_id), IDX(parent_id), IDX(user_id), IDX_COMPUESTO(company_id, parent_id, order)

---

## 5. Diagrama de Relaciones ERD

```
CATALOGOS (datos de referencia)
================================

document_types (id, name, code)
environmental_authorities (id, name, acronym, jurisdiction)
departments (id, name, code)
municipalities (id, department_id, name, code)
  municipalities.department_id --> departments.id

environmental_media (id, name, description)
monitoring_phases (id, name, order)
indicator_frequencies (id, name, months_interval)
monitoring_tools (id, name, description)


NUCLEO MULTI-TENANT
====================

companies (id, name, nit, ciiu_code, municipality_id, ...)
  companies.municipality_id --> municipalities.id

users (id, company_id*, document_type_id, email, ...)
  users.company_id --> companies.id
  users.document_type_id --> document_types.id

[PIVOT] company_user (company_id, user_id)
  company_user.company_id --> companies.id
  company_user.user_id --> users.id


JERARQUIA DE NEGOCIO
=======================

programs (id, company_id*, environmental_medium_id, name, code, ...)
  programs.company_id --> companies.id
  programs.environmental_medium_id --> environmental_media.id

projects (id, company_id*, program_id, name, status, ...)
  projects.company_id --> companies.id
  projects.program_id --> programs.id

[PIVOT] project_user (project_id, user_id)
  project_user.project_id --> projects.id
  project_user.user_id --> users.id

monitorings (id, company_id*, project_id, municipality_id, environmental_authority_id, ...)
  monitorings.company_id --> companies.id
  monitorings.project_id --> projects.id
  monitorings.municipality_id --> municipalities.id
  monitorings.environmental_authority_id --> environmental_authorities.id

worksheets (id, company_id*, obligation_id, monitoring_id, monitoring_tool [text], monitoring_phase [text], ...)
  worksheets.company_id --> companies.id
  worksheets.obligation_id --> obligations.id  [NUEVO flujo principal v1.1]
  worksheets.monitoring_id --> monitorings.id  [flujo secundario - trazabilidad]

indicators (id, company_id*, worksheet_id, indicator_frequency_id, ...)
  indicators.company_id --> companies.id
  indicators.worksheet_id --> worksheets.id
  indicators.indicator_frequency_id --> indicator_frequencies.id

activities (id, company_id*, indicator_id, status, scheduled_date, executed_date, ...)
  activities.company_id --> companies.id
  activities.indicator_id --> indicators.id

[PIVOT] activity_user (activity_id, user_id)
  activity_user.activity_id --> activities.id
  activity_user.user_id --> users.id

evidences (id, company_id*, activity_id, uploaded_by, original_name, storage_path, ...)
  evidences.company_id --> companies.id
  evidences.activity_id --> activities.id
  evidences.uploaded_by --> users.id

alerts (id, company_id*, indicator_id, user_id, type, due_date, status, ...)
  alerts.company_id --> companies.id
  alerts.indicator_id --> indicators.id
  alerts.user_id --> users.id

indicator_controls (id, company_id*, indicator_id, measured_by, period_start, period_end, compliance_status, ...)
  indicator_controls.company_id --> companies.id
  indicator_controls.indicator_id --> indicators.id
  indicator_controls.measured_by --> users.id


-- v1.1 NUEVAS ENTIDADES --

organization_projects (id, company_id*, municipality_id, name, code, status, ...)
  organization_projects.company_id --> companies.id
  organization_projects.municipality_id --> municipalities.id

obligations (id, company_id*, monitoring_id, environmental_authority_id, name, status, ...)
  obligations.company_id --> companies.id
  obligations.monitoring_id --> monitorings.id  [opcional, trazabilidad]
  obligations.environmental_authority_id --> environmental_authorities.id

[PIVOT] obligation_organization_project (obligation_id, organization_project_id)
  obligation_organization_project.obligation_id --> obligations.id
  obligation_organization_project.organization_project_id --> organization_projects.id

org_chart_positions (id, company_id*, parent_id [self], user_id, position_title, ...)
  org_chart_positions.company_id --> companies.id
  org_chart_positions.parent_id --> org_chart_positions.id  [self-referential]
  org_chart_positions.user_id --> users.id


JERARQUIA PRINCIPAL v1.1 (nuevo flujo):
companies
  ├── organization_projects ←M:N→ obligations
  │                                   └── worksheets
  │                                         └── indicators
  │                                               └── activities
  │                                                     └── evidences
  │                                                     └── [activity_user]
  │                                               └── alerts
  │                                               └── indicator_controls
  └── [organigrama] org_chart_positions (arbol self-referential)

JERARQUIA SECUNDARIA v1.1 (conservada para trazabilidad regulatoria):
companies
  └── programs
        └── projects
              └── monitorings
                    └── [worksheets via monitoring_id - flujo secundario]

* company_id desnormalizado para eficiencia en queries multi-tenant
```

---

## 6. Estimacion de Volumetria

| Tabla | Filas en 1 ano | Filas en 3 anos | Notas |
|---|---|---|---|
| `companies` | 50 | 150 | Crecimiento por ventas |
| `users` | 500 | 1.500 | ~10 usuarios por empresa |
| `programs` | 500 | 1.500 | ~10 programas por empresa |
| `projects` | 1.000 | 3.000 | ~2 proyectos por programa |
| `monitorings` | 2.000 | 6.000 | ~2 fichas PMA por proyecto |
| `organization_projects` | 500 | 1.500 | ~10 proyectos por empresa — v1.1 |
| `obligations` | 5.000 | 15.000 | ~10 obligaciones por proyecto — v1.1 |
| `org_chart_positions` | 1.000 | 3.000 | ~20 nodos por empresa — v1.1 |
| `worksheets` | 10.000 | 30.000 | ~2 fichas de trabajo por obligacion (flujo principal v1.1) |
| `indicators` | 50.000 | 150.000 | ~5 indicadores por worksheet |
| `activities` | 200.000 | 600.000 | ~4 actividades por indicador |
| `evidences` | 500.000 | 1.500.000 | ~2-3 evidencias por actividad |
| `alerts` | 100.000 | 300.000 | ~2 alertas por indicador |
| `indicator_controls` | 150.000 | 450.000 | Registros de cumplimiento periodico |

**Tablas criticas por volumen:** `evidences`, `activities`, `indicators`, `indicator_controls`

**Estrategia para escala:** Los indices en `company_id` garantizan que cada query solo acceda a datos de una empresa. La desnormalizacion de `company_id` en tablas descendientes evita JOINs costosos para verificar tenancy.

---

## 7. Indices y Estrategia de Busqueda

### Queries criticos y sus indices

| Query | Indices usados |
|---|---|
| Listar proyectos de una empresa | `projects.company_id` |
| Dashboard de cumplimiento de indicadores | `indicator_controls.company_id`, `indicator_controls.compliance_status` |
| Alertas activas de un usuario | `alerts.user_id`, `alerts.status`, `alerts.due_date` |
| Actividades pendientes de un proyecto | `activities.company_id`, `activities.status`, `activities.scheduled_date` |
| Buscar empresa por NIT | `companies.nit` (UNIQUE) |
| Buscar usuario por email | `users.email` (UNIQUE) |
| Historial de indicador en periodo | `indicator_controls.indicator_id`, `indicator_controls.period_start` |

### Indices compuestos recomendados (fase 2)

```sql
-- Para dashboard de cumplimiento por empresa y periodo
CREATE INDEX idx_indicator_controls_company_period
  ON indicator_controls (company_id, period_start, period_end);

-- Para alertas activas proximas a vencer
CREATE INDEX idx_alerts_company_status_due
  ON alerts (company_id, status, due_date);

-- Para actividades de un indicador ordenadas por fecha
CREATE INDEX idx_activities_indicator_date
  ON activities (indicator_id, scheduled_date);
```

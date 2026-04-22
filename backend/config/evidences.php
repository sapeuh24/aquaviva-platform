<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Retención de evidencias
    |--------------------------------------------------------------------------
    | Años que se conservan los archivos de evidencias antes de ser eliminados
    | permanentemente por el comando: php artisan evidences:purge
    |
    | Valor por defecto: 5 años (requerimiento regulatorio ambiental colombiano)
    | Para cambiar: modificar este valor o definir EVIDENCE_RETENTION_YEARS en .env
    |
    */
    'retention_years' => (int) env('EVIDENCE_RETENTION_YEARS', 5),

    /*
    |--------------------------------------------------------------------------
    | Tipos de archivo permitidos
    |--------------------------------------------------------------------------
    | MIME types aceptados para el cargue de evidencias.
    | Modificar esta lista para agregar o restringir tipos de archivo.
    |
    */
    'allowed_mime_types' => [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'video/mp4',
        'video/quicktime',
        'video/x-msvideo',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tamaño máximo de archivo
    |--------------------------------------------------------------------------
    | Tamaño máximo en kilobytes para archivos de evidencia.
    | 51200 = 50MB
    |
    */
    'max_file_size_kb' => (int) env('EVIDENCE_MAX_FILE_SIZE_KB', 51200),

    /*
    |--------------------------------------------------------------------------
    | Ruta de almacenamiento
    |--------------------------------------------------------------------------
    | Ruta base dentro del disco 'local' donde se guardan las evidencias.
    | Estructura: {storage_path}/{activity_id}/{filename}
    |
    */
    'storage_path' => env('EVIDENCE_STORAGE_PATH', 'evidences'),
];

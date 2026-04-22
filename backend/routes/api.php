<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\EvidenceController;
use App\Http\Controllers\Api\IndicatorController;
use App\Http\Controllers\Api\ObligationController;
use App\Http\Controllers\Api\OrganizationProjectController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorksheetController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->group(function (): void {

    Route::get('health', fn () => response()->json(['status' => 'ok']))->name('health');

    Route::prefix('auth')->name('auth.')->group(function (): void {
        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('me', [AuthController::class, 'me'])->name('me');
        });
    });

    Route::middleware('auth:sanctum')->group(function (): void {

        // Empresas
        Route::apiResource('companies', CompanyController::class);

        // Usuarios
        Route::apiResource('users', UserController::class);

        // Programas ambientales
        Route::get('programs/{program}/projects', [ProgramController::class, 'projects'])->name('programs.projects');
        Route::apiResource('programs', ProgramController::class);

        // Proyectos de la organizacion
        Route::post('organization-projects/{organizationProject}/obligations/{obligation}', [OrganizationProjectController::class, 'attachObligation'])->name('organization-projects.obligations.attach');
        Route::delete('organization-projects/{organizationProject}/obligations/{obligation}', [OrganizationProjectController::class, 'detachObligation'])->name('organization-projects.obligations.detach');
        Route::apiResource('organization-projects', OrganizationProjectController::class);

        // Obligaciones ambientales
        Route::get('obligations/by-project/{organizationProject}', [ObligationController::class, 'byOrganizationProject'])->name('obligations.by-project');
        Route::apiResource('obligations', ObligationController::class);

        // Fichas de monitoreo (worksheets)
        Route::apiResource('worksheets', WorksheetController::class);

        // Indicadores
        Route::apiResource('indicators', IndicatorController::class);

        // Actividades
        Route::post('activities/{activity}/users/{user}', [ActivityController::class, 'assignUser'])->name('activities.users.assign');
        Route::delete('activities/{activity}/users/{user}', [ActivityController::class, 'unassignUser'])->name('activities.users.unassign');
        Route::apiResource('activities', ActivityController::class);

        // Evidencias
        Route::get('evidences/{evidence}/download', [EvidenceController::class, 'download'])->name('evidences.download');
        Route::apiResource('evidences', EvidenceController::class)->except(['update']);

        // Alertas
        Route::patch('alerts/{alert}/dismiss', [AlertController::class, 'dismiss'])->name('alerts.dismiss');
        Route::apiResource('alerts', AlertController::class)->only(['index', 'show']);
    });
});

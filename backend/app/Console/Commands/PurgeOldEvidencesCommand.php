<?php

namespace App\Console\Commands;

use App\Models\Evidence;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PurgeOldEvidencesCommand extends Command
{
    protected $signature = 'evidences:purge
                            {--dry-run : Simula la eliminación sin borrar ningún archivo ni registro}';

    protected $description = 'Elimina físicamente evidencias cuyo archivo supera el tiempo de retención configurado.';

    public function handle(): int
    {
        $retentionYears = config('evidences.retention_years', 5);
        $cutoffDate = now()->subYears($retentionYears);
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->components->warn('Modo dry-run activo: no se eliminará ningún archivo ni registro.');
        }

        $this->components->info(
            "Buscando evidencias creadas antes de {$cutoffDate->toDateString()} (retención: {$retentionYears} años)."
        );

        $totalProcessed = 0;
        $totalFilesFound = 0;
        $totalFilesDeleted = 0;
        $totalFailed = 0;

        Evidence::withTrashed()
            ->where('created_at', '<=', $cutoffDate)
            ->chunk(100, function (Collection $evidences) use (
                $isDryRun,
                &$totalProcessed,
                &$totalFilesFound,
                &$totalFilesDeleted,
                &$totalFailed
            ): void {
                foreach ($evidences as $evidence) {
                    $totalProcessed++;
                    $fileExists = Storage::exists($evidence->storage_path);

                    if ($fileExists) {
                        $totalFilesFound++;
                    }

                    if ($isDryRun) {
                        $this->line(
                            "  [dry-run] ID {$evidence->id} — {$evidence->storage_path}"
                            . ($fileExists ? ' (archivo encontrado)' : ' (archivo no encontrado en storage)')
                        );
                        continue;
                    }

                    $deleted = $this->deleteFile($evidence->storage_path, $evidence->id);

                    if ($deleted) {
                        $totalFilesDeleted++;
                    } elseif ($fileExists) {
                        $totalFailed++;
                    }

                    $evidence->forceDelete();
                }
            });

        $this->reportResults($isDryRun, $totalProcessed, $totalFilesFound, $totalFilesDeleted, $totalFailed);

        if (!$isDryRun) {
            Log::channel('daily')->info('evidences:purge completado', [
                'procesadas'       => $totalProcessed,
                'archivos_encontrados' => $totalFilesFound,
                'archivos_eliminados'  => $totalFilesDeleted,
                'fallidos'         => $totalFailed,
                'cutoff_date'      => $cutoffDate->toDateTimeString(),
            ]);
        }

        return Command::SUCCESS;
    }

    /**
     * Intenta eliminar el archivo físico del storage.
     * Retorna true si la eliminación fue exitosa, false si hubo un error.
     */
    private function deleteFile(string $storagePath, int $evidenceId): bool
    {
        try {
            return Storage::delete($storagePath);
        } catch (\Throwable $e) {
            Log::channel('daily')->error('evidences:purge — fallo al eliminar archivo', [
                'evidence_id'  => $evidenceId,
                'storage_path' => $storagePath,
                'error'        => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Muestra el resumen final de la ejecución en la consola.
     */
    private function reportResults(
        bool $isDryRun,
        int $totalProcessed,
        int $totalFilesFound,
        int $totalFilesDeleted,
        int $totalFailed
    ): void {
        $this->newLine();

        if ($isDryRun) {
            $this->components->info("Dry-run finalizado. Se procesarían {$totalProcessed} evidencias ({$totalFilesFound} archivos encontrados en storage).");
            return;
        }

        $this->components->info("Purga completada.");
        $this->table(
            ['Métrica', 'Cantidad'],
            [
                ['Evidencias procesadas',       $totalProcessed],
                ['Archivos encontrados',         $totalFilesFound],
                ['Archivos eliminados',          $totalFilesDeleted],
                ['Fallos al eliminar archivo',   $totalFailed],
            ]
        );
    }
}

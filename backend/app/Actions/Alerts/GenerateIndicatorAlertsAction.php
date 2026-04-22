<?php

namespace App\Actions\Alerts;

use App\Models\Alert;
use App\Models\Indicator;
use Illuminate\Support\Carbon;

class GenerateIndicatorAlertsAction
{
    public function execute(): int
    {
        $thirtyDaysFromNow = Carbon::today()->addDays(30);
        $created           = 0;

        Indicator::query()
            ->whereNotNull('next_due_date')
            ->where('next_due_date', '<=', $thirtyDaysFromNow)
            ->where('is_active', true)
            ->whereDoesntHave('alerts', fn ($q) => $q->where('status', 'activa'))
            ->each(function (Indicator $indicator) use (&$created): void {
                Alert::create([
                    'company_id'   => $indicator->company_id,
                    'indicator_id' => $indicator->id,
                    'type'         => 'vencimiento',
                    'title'        => "Indicador por vencer: {$indicator->name}",
                    'message'      => "El indicador '{$indicator->name}' vence el {$indicator->next_due_date->format('d/m/Y')}.",
                    'due_date'     => $indicator->next_due_date,
                    'status'       => 'activa',
                ]);

                $created++;
            });

        return $created;
    }
}

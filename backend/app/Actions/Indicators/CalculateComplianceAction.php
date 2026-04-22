<?php

namespace App\Actions\Indicators;

use App\Models\Indicator;

class CalculateComplianceAction
{
    public function execute(Indicator $indicator): float
    {
        $total = $indicator->activities()->count();

        if ($total === 0) {
            return 0.0;
        }

        $completed = $indicator->activities()->where('compliance_status', 'completed')->count();

        return round(($completed / $total) * 100, 2);
    }
}

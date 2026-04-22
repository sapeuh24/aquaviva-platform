<?php

namespace App\Http\Resources;

use App\Actions\Indicators\CalculateComplianceAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndicatorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $complianceAction = app(CalculateComplianceAction::class);

        return [
            'id'                    => $this->id,
            'worksheet_id'          => $this->worksheet_id,
            'name'                  => $this->name,
            'objective'             => $this->objective,
            'target'                => $this->target,
            'next_due_date'         => $this->next_due_date?->toDateString(),
            'is_active'             => $this->is_active,
            'compliance_percentage' => $complianceAction->execute($this->resource),
            'frequency'             => $this->whenLoaded('indicatorFrequency', fn () => [
                'id'   => $this->indicatorFrequency->id,
                'name' => $this->indicatorFrequency->name,
            ]),
            'activities_count' => $this->whenCounted('activities', fn () => $this->activities_count),
            'created_at'       => $this->created_at?->toISOString(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObligationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $totalWorksheets    = $this->worksheets_count ?? 0;
        $completedActivities = 0;
        $totalActivities     = 0;

        if ($this->relationLoaded('worksheets')) {
            foreach ($this->worksheets as $worksheet) {
                if ($worksheet->relationLoaded('indicators')) {
                    foreach ($worksheet->indicators as $indicator) {
                        if ($indicator->relationLoaded('activities')) {
                            $totalActivities    += $indicator->activities->count();
                            $completedActivities += $indicator->activities
                                ->where('compliance_status', 'completed')
                                ->count();
                        }
                    }
                }
            }
        }

        $compliancePercentage = $totalActivities > 0
            ? round(($completedActivities / $totalActivities) * 100, 2)
            : 0.0;

        return [
            'id'                      => $this->id,
            'name'                    => $this->name,
            'description'             => $this->description,
            'resolution_number'       => $this->resolution_number,
            'resolution_date'         => $this->resolution_date?->toDateString(),
            'instrument_type'         => $this->instrument_type,
            'legal_basis'             => $this->legal_basis,
            'environmental_medium'    => $this->environmental_medium,
            'obligation_type'         => $this->obligation_type,
            'compliance_deadline'     => $this->compliance_deadline?->toDateString(),
            'compliance_frequency'    => $this->compliance_frequency,
            'status'                  => $this->status,
            'environmental_authority' => $this->whenLoaded('environmentalAuthority', fn () => [
                'id'   => $this->environmentalAuthority->id,
                'name' => $this->environmentalAuthority->name,
                'code' => $this->environmentalAuthority->code,
            ]),
            'organization_projects' => $this->whenLoaded('organizationProjects', fn () => $this->organizationProjects->map(fn ($p) => [
                'id'   => $p->id,
                'name' => $p->name,
            ])->values()->all()),
            'worksheets_count'      => $this->whenCounted('worksheets', fn () => $this->worksheets_count),
            'compliance_percentage' => $compliancePercentage,
            'created_at'            => $this->created_at?->toISOString(),
        ];
    }
}

<?php

namespace App\Repositories;

use App\DataTransferObjects\Obligations\ObligationData;
use App\Models\Obligation;
use App\Repositories\Contracts\ObligationRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ObligationRepository implements ObligationRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator
    {
        return Obligation::query()
            ->with(['environmentalAuthority', 'monitoring', 'organizationProjects'])
            ->when(
                isset($filters['search']),
                fn ($q) => $q->where(function ($q) use ($filters): void {
                    $q->where('name', 'like', "%{$filters['search']}%")
                        ->orWhere('resolution_number', 'like', "%{$filters['search']}%");
                }),
            )
            ->when(
                isset($filters['status']),
                fn ($q) => $q->where('status', $filters['status']),
            )
            ->when(
                isset($filters['environmental_authority_id']),
                fn ($q) => $q->where('environmental_authority_id', $filters['environmental_authority_id']),
            )
            ->when(
                isset($filters['deadline_from']),
                fn ($q) => $q->where('compliance_deadline', '>=', $filters['deadline_from']),
            )
            ->when(
                isset($filters['deadline_to']),
                fn ($q) => $q->where('compliance_deadline', '<=', $filters['deadline_to']),
            )
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findOrFail(int $id): Obligation
    {
        return Obligation::with(['environmentalAuthority', 'monitoring', 'organizationProjects'])
            ->withCount('worksheets')
            ->findOrFail($id);
    }

    public function create(ObligationData $data): Obligation
    {
        return Obligation::create([
            'company_id'                => $data->company_id,
            'monitoring_id'             => $data->monitoring_id,
            'environmental_authority_id'=> $data->environmental_authority_id,
            'resolution_number'         => $data->resolution_number,
            'resolution_date'           => $data->resolution_date,
            'instrument_type'           => $data->instrument_type,
            'name'                      => $data->name,
            'description'               => $data->description,
            'legal_basis'               => $data->legal_basis,
            'environmental_medium'      => $data->environmental_medium,
            'obligation_type'           => $data->obligation_type,
            'compliance_deadline'       => $data->compliance_deadline,
            'compliance_frequency'      => $data->compliance_frequency,
            'status'                    => $data->status,
        ]);
    }

    public function update(Obligation $obligation, ObligationData $data): Obligation
    {
        $obligation->update([
            'company_id'                => $data->company_id,
            'monitoring_id'             => $data->monitoring_id,
            'environmental_authority_id'=> $data->environmental_authority_id,
            'resolution_number'         => $data->resolution_number,
            'resolution_date'           => $data->resolution_date,
            'instrument_type'           => $data->instrument_type,
            'name'                      => $data->name,
            'description'               => $data->description,
            'legal_basis'               => $data->legal_basis,
            'environmental_medium'      => $data->environmental_medium,
            'obligation_type'           => $data->obligation_type,
            'compliance_deadline'       => $data->compliance_deadline,
            'compliance_frequency'      => $data->compliance_frequency,
            'status'                    => $data->status,
        ]);

        return $obligation->fresh(['environmentalAuthority', 'monitoring', 'organizationProjects']);
    }

    public function softDelete(Obligation $obligation): void
    {
        $obligation->delete();
    }
}

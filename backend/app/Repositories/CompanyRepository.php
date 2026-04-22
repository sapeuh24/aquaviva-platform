<?php

namespace App\Repositories;

use App\DataTransferObjects\Companies\CompanyData;
use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator
    {
        return Company::query()
            ->with(['municipality.department'])
            ->when(
                isset($filters['search']),
                fn ($q) => $q->where(function ($q) use ($filters): void {
                    $q->where('name', 'like', "%{$filters['search']}%")
                        ->orWhere('nit', 'like', "%{$filters['search']}%");
                }),
            )
            ->when(
                isset($filters['status']),
                fn ($q) => $q->where('is_active', filter_var($filters['status'], FILTER_VALIDATE_BOOLEAN)),
            )
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findOrFail(int $id): Company
    {
        return Company::with(['municipality.department'])->findOrFail($id);
    }

    public function create(CompanyData $data): Company
    {
        return Company::create([
            'name'             => $data->name,
            'nit'              => $data->nit,
            'ciiu_code'        => $data->ciiu_code,
            'ciiu_description' => $data->ciiu_description,
            'municipality_id'  => $data->municipality_id,
            'phone'            => $data->phone,
            'email'            => $data->email,
            'address'          => $data->address,
            'is_active'        => $data->is_active,
        ]);
    }

    public function update(Company $company, CompanyData $data): Company
    {
        $company->update([
            'name'             => $data->name,
            'nit'              => $data->nit,
            'ciiu_code'        => $data->ciiu_code,
            'ciiu_description' => $data->ciiu_description,
            'municipality_id'  => $data->municipality_id,
            'phone'            => $data->phone,
            'email'            => $data->email,
            'address'          => $data->address,
            'is_active'        => $data->is_active,
        ]);

        return $company->fresh(['municipality.department']);
    }

    public function softDelete(Company $company): void
    {
        $company->delete();
    }
}

<?php

namespace App\Repositories;

use App\DataTransferObjects\Users\UserData;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator
    {
        return User::query()
            ->with(['company', 'documentType', 'roles'])
            ->when(
                isset($filters['search']),
                fn ($q) => $q->where(function ($q) use ($filters): void {
                    $q->where('first_name', 'like', "%{$filters['search']}%")
                        ->orWhere('last_name', 'like', "%{$filters['search']}%")
                        ->orWhere('email', 'like', "%{$filters['search']}%")
                        ->orWhere('document_number', 'like', "%{$filters['search']}%");
                }),
            )
            ->when(
                isset($filters['company_id']),
                fn ($q) => $q->where('company_id', $filters['company_id']),
            )
            ->when(
                isset($filters['is_active']),
                fn ($q) => $q->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN)),
            )
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate($perPage);
    }

    public function findOrFail(int $id): User
    {
        return User::with(['company.municipality.department', 'documentType', 'roles'])->findOrFail($id);
    }

    public function create(UserData $data): User
    {
        $user = User::create([
            'company_id'       => $data->company_id,
            'document_type_id' => $data->document_type_id,
            'document_number'  => $data->document_number,
            'first_name'       => $data->first_name,
            'last_name'        => $data->last_name,
            'email'            => $data->email,
            'phone'            => $data->phone,
            'password'         => Hash::make($data->password),
            'is_active'        => $data->is_active,
        ]);

        if (! empty($data->roles)) {
            $user->syncRoles($data->roles);
        }

        return $user->load(['company', 'roles']);
    }

    public function update(User $user, UserData $data): User
    {
        $attributes = [
            'company_id'       => $data->company_id,
            'document_type_id' => $data->document_type_id,
            'document_number'  => $data->document_number,
            'first_name'       => $data->first_name,
            'last_name'        => $data->last_name,
            'email'            => $data->email,
            'phone'            => $data->phone,
            'is_active'        => $data->is_active,
        ];

        if ($data->password !== null) {
            $attributes['password'] = Hash::make($data->password);
        }

        $user->update($attributes);
        $user->syncRoles($data->roles);

        return $user->fresh(['company.municipality.department', 'roles']);
    }

    public function softDelete(User $user): void
    {
        $user->delete();
    }
}

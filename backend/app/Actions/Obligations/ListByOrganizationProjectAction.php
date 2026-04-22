<?php

namespace App\Actions\Obligations;

use App\Models\OrganizationProject;
use Illuminate\Database\Eloquent\Collection;

class ListByOrganizationProjectAction
{
    public function execute(OrganizationProject $organizationProject): Collection
    {
        return $organizationProject->obligations()
            ->with(['environmentalAuthority', 'monitoring'])
            ->withCount('worksheets')
            ->get();
    }
}

<?php

namespace App\Actions\OrganizationProjects;

use App\Models\Obligation;
use App\Models\OrganizationProject;

class DetachObligationAction
{
    public function execute(OrganizationProject $project, Obligation $obligation): void
    {
        $project->obligations()->detach($obligation->id);
    }
}

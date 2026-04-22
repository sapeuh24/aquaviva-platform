<?php

namespace App\Actions\OrganizationProjects;

use App\Models\Obligation;
use App\Models\OrganizationProject;

class AttachObligationAction
{
    public function execute(OrganizationProject $project, Obligation $obligation): void
    {
        $project->obligations()->syncWithoutDetaching([$obligation->id]);
    }
}

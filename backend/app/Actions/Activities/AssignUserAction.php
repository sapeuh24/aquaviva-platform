<?php

namespace App\Actions\Activities;

use App\Models\Activity;
use App\Models\User;

class AssignUserAction
{
    public function execute(Activity $activity, User $user): void
    {
        $activity->users()->syncWithoutDetaching([$user->id]);
    }
}

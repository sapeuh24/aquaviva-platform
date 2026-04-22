<?php

namespace App\Actions\Activities;

use App\Models\Activity;
use App\Models\User;

class UnassignUserAction
{
    public function execute(Activity $activity, User $user): void
    {
        $activity->users()->detach($user->id);
    }
}

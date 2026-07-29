<?php

namespace App\Policies;

use App\Models\Measurement;
use App\Models\User;

class MeasurementPolicy
{
    public function view(User $user, Measurement $measurement): bool
    {
        return $user->isAdmin() || $measurement->child?->user_id === $user->id;
    }
}

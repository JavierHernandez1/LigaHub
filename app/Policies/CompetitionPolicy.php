<?php

namespace App\Policies;

use App\Models\Competition;
use App\Models\User;

class CompetitionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Competition $competition): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'organizer']);
    }

    public function update(User $user, Competition $competition): bool
    {
        return $user->hasRole(['admin', 'organizer']);
    }

    public function delete(User $user, Competition $competition): bool
    {
        return $user->hasRole('admin');
    }
}

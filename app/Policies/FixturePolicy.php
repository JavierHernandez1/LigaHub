<?php

namespace App\Policies;

use App\Models\Fixture;
use App\Models\User;

class FixturePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Fixture $fixture): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'organizer', 'referee']);
    }

    public function update(User $user, Fixture $fixture): bool
    {
        return $user->hasRole(['admin', 'organizer', 'referee']);
    }

    public function delete(User $user, Fixture $fixture): bool
    {
        return $user->hasRole(['admin', 'organizer']);
    }
}

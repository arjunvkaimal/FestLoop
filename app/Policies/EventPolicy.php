<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Event $event): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isCoordinator();
    }

    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->coordinator_id || $user->isAdmin();
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->id === $event->coordinator_id || $user->isAdmin();
    }

    public function manageRegistrations(User $user, Event $event): bool
    {
        return $user->id === $event->coordinator_id || $user->isAdmin();
    }
}

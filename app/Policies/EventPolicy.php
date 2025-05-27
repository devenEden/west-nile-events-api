<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    /**
     * Determine whether the user can create models.
     */

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {

        return $user->is($event->user);
    }

    public function  userOwnsEvent(User $user, Event $event): bool
    {
        return $user->is($event->user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->is($event->user);
    }
}

<?php

namespace App\Policies;

use App\Models\Inbox;
use App\Models\User;

class InboxPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isOperator();
    }

    public function view(User $user, Inbox $inbox): bool
    {
        return $user->isOperator();
    }

    public function create(User $user): bool
    {
        return $user->isOperator();
    }

    public function update(User $user, Inbox $inbox): bool
    {
        return $user->isOperator();
    }

    public function delete(User $user, Inbox $inbox): bool
    {
        return $user->isOperator();
    }

    public function syncOperators(User $user, Inbox $inbox): bool
    {
        return $user->isOperator();
    }
}

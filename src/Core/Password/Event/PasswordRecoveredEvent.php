<?php

namespace App\Core\Password\Event;

use App\Entity\User;

final class PasswordRecoveredEvent
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

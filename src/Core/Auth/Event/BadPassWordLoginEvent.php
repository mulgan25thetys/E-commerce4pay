<?php

namespace App\Core\Auth\Event;
use App\Entity\User;

class BadPassWordLoginEvent
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
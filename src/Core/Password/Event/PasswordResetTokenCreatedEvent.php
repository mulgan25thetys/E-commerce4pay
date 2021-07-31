<?php

namespace App\Core\Password\Event;

use App\Entity\User;
use App\Entity\PasswordResetToken;

final class PasswordResetTokenCreatedEvent
{
    private PasswordResetToken $token;

    public function __construct(PasswordResetToken $token)
    {
        $this->token = $token;
    }

    public function getUser(): User
    {
        return $this->token->getUser();
    }

    public function getToken(): PasswordResetToken
    {
        return $this->token;
    }
}

<?php

namespace App\Core\Password\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Exception renvoyée si un Token de reinitialisation de password est expirer.
 */
class TokenExpiredException extends AuthenticationException
{
    public function __construct()
    {
        parent::__construct('', 0, null);
    }

    public function getMessageKey(): string
    {
        return 'Ongoing password reset.';
    }
}

<?php


namespace App\Core\Auth\Security;


use App\Core\Auth\Exception\TooManyBadCredentialsException;
use App\Core\Auth\Exception\UserBannedException;
use App\Core\Auth\Exception\UserNotFoundException;
use App\Core\Auth\Service\LoginAttemptService;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
class UserChecker implements  UserCheckerInterface
{
    private LoginAttemptService $loginAttemptService;

    public function __construct(LoginAttemptService $loginAttemptService)
    {
        $this->loginAttemptService = $loginAttemptService;
    }
    /**
     * Vérifie que l'utilisateur a le droit de se connecter.
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if ($user instanceof User && $this->loginAttemptService->limitReachedFor($user)) {
            throw new TooManyBadCredentialsException();
        }
        return;
    }

    /**
     * Vérifie que l'utilisateur connecté a le droit de continuer.
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if ($user instanceof User && $user->isBanned()) {
            throw new UserBannedException();
        }
        if ($user instanceof User && null !== $user->getConfirmationToken()) {
            throw new UserNotFoundException();
        }
        return;
    }

}
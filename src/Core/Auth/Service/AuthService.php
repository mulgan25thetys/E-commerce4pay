<?php


namespace App\Core\Auth\Service;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Event\LogoutEvent;
/**
 * Service pour simplifier la communication avec l'authentication et offrir un type plus strict.
 */
class AuthService
{
    private  TokenStorageInterface $tokenStorage;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
    }
    public function getUserOrNull(): ?User
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }

        $user = $token->getUser();
        if (!\is_object($user)) {
            return null;
        }

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }

    public function getUser(): User
    {
        $user = $this->getUserOrNull();
        if (null === $user) {
            throw new AccessDeniedException();
        }

        return $user;
    }
    public function logout(?Request $request = null): void
    {
        $request = $request ?: new Request();
        //$this->eventDispatcher->dispatch(new LogoutEvent($request, $this->tokenStorage->getToken()));
        $this->tokenStorage->setToken(null);
    }
}
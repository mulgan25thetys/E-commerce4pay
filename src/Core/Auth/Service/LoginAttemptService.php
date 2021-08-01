<?php


namespace App\Core\Auth\Service;


use App\Core\Auth\Repository\LoginAttemptRepository;
use App\Entity\LoginAttempt;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class LoginAttemptService
{
    const ATTEMPTS = 3;
    private  LoginAttemptRepository  $repository;
    private  EntityManagerInterface  $em;

    public function __construct(
        LoginAttemptRepository $repository,
        EntityManagerInterface $em
    ) {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function addAttempt(User $user): void
    {
        // TODO : Envoyer un email au bout du Xème essai
        $attempt = (new LoginAttempt())->setUser($user);
        $this->em->persist($attempt);
        $this->em->flush();
    }
    public function limitReachedFor(User $user): bool
    {
        return $this->repository->countRecentFor($user, 30) >= self::ATTEMPTS;
    }

}
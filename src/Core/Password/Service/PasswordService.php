<?php


namespace App\Core\Password\Service;


use App\Core\Auth\Exception\UserNotFoundException;
use App\Core\Auth\Repository\UserRepository;
use App\Core\Password\Data\PasswordResetRequestData;
use App\Core\Password\Event\PasswordRecoveredEvent;
use App\Core\Password\Event\PasswordResetTokenCreatedEvent;
use App\Core\Password\Exception\OngoingPasswordResetException;
use App\Core\Password\Repository\PasswordResetTokenRepository;
use App\Entity\PasswordResetToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordService
{
    const EXPIRE_IN = 30; // Temps d'expiration d'un token

    private UserRepository $userRepository;
    private PasswordResetTokenRepository $tokenRepository;
    private EntityManagerInterface $em;
    private TokenGeneratorService $generator;
    private EventDispatcherInterface $dispatcher;
    private UserPasswordEncoderInterface $encoder;


    public function __construct(
        UserRepository $userRepository,
        PasswordResetTokenRepository $tokenRepository,
        TokenGeneratorService $generator,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->em = $em;
        $this->generator = $generator;
        $this->dispatcher = $dispatcher;
        $this->encoder = $encoder;
    }
    public function isExpired(PasswordResetToken $token): bool
    {
        $expirationDate = new \DateTime('-'.self::EXPIRE_IN.' minutes');

        return $token->getCreatedAt() < $expirationDate;
    }

    /**
     * Lance une demande de réinitialisation de mot de passe.
     *
     * @throws \App\Core\Password\Exception\OngoingPasswordResetException
     * @throws UserNotFoundException
     */
    public function resetPassword(PasswordResetRequestData $data): void
    {
        $user = $this->userRepository->findOneBy(['email' => $data->getEmail(), 'bannedAt' => null]);
        if (null === $user) {
            throw new UserNotFoundException();
        }
        $token = $this->tokenRepository->findOneBy(['user' => $user]);
        if (null !== $token && !$this->isExpired($token)) {
            throw new OngoingPasswordResetException();
        }
        if (null === $token) {
            $token = new PasswordResetToken();
            $this->em->persist($token);
        }
        $token->setUser($user)
            ->setCreatedAt(new \DateTime())
            ->setToken($this->generator->generate());
        $this->em->flush();
        $this->dispatcher->dispatch(new PasswordResetTokenCreatedEvent($token));
    }

    public function updatePassword(string $password, PasswordResetToken $token): void
    {
        $user = $token->getUser();
        $user->setConfirmationToken(null);
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $this->em->remove($token);
        $this->em->flush();
        $this->dispatcher->dispatch(new PasswordRecoveredEvent($user));
    }
}
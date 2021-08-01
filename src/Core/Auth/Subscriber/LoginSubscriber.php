<?php


namespace App\Core\Auth\Subscriber;


use App\Core\Auth\Event\BadPassWordLoginEvent;
use App\Core\Auth\Service\LoginAttemptService;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    private LoginAttemptService  $service;
    private  EntityManagerInterface $em;
    public function __construct(LoginAttemptService $service, EntityManagerInterface $em)
    {
        $this->service = $service;
        $this->em = $em;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BadPasswordLoginEvent::class => 'onAuthenticationFailure',
            InteractiveLoginEvent::class => 'onLogin',
        ];
    }

    public function onAuthenticationFailure(BadPasswordLoginEvent $event): void
    {
        $this->service->addAttempt($event->getUser());
    }

    public function onLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        $event->getRequest()->getClientIp();
        if ($user instanceof User) {
            $ip = $event->getRequest()->getClientIp();
            if ($ip !== $user->getLastLoginIp()) {
                //TODO plein de truc peuvent ce passee ici mais pour le moment je fait ca.
                $user->setLastLoginIp($ip);
            }
            $user->setLastLoginAt(new \DateTimeImmutable());
            $this->em->flush();
        }
    }

}
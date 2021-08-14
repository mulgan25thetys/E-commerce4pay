<?php

namespace App\Security;

use App\Entity\Content;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ContentVoter extends Voter
{
    const PROGRESS = 'progress';

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, [
                self::PROGRESS,
            ]) && $subject instanceof Content;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute( $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        return $user instanceof User;
    }
}

<?php

namespace App\Security\Voter;

use App\Entity\Client;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientVoter extends Voter
{
    public const EDIT = 'edit';
    public const CREATE= 'create';

    protected function supports($attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::CREATE], true)
            && $subject instanceof Client;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                return $this->canEdit($subject, $user);
                break;
            case self::CREATE:
                // logic to determine if the user can VIEW
                return $this->canCreate($user);
                break;
        }

        return false;
    }

    private function canEdit(Client $client, UserInterface $user): bool
    {
        return $user->hasRoles('ROLE_ADMIN') || $client->getUser()->getId() === $user->getId();
    }

    private function canCreate(UserInterface $user): bool
    {
        return $user->hasRoles('ROLE_USER') || $user->hasRoles('ROLE_ADMIN');
    }
}

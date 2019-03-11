<?php

namespace App\Security\Voter;

use App\Entity\Intervention;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class InterventionVoter extends Voter
{

    public const EDIT = 'edit';
    public const CREATE= 'create';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::CREATE], true)
            && $subject instanceof Intervention;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
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
                // logic to determine if the user can CREATE
                return $this->canCreate($user);
                break;
        }

        return false;
    }

    private function canEdit(Intervention $intervention, UserInterface $user): bool
    {
        return $user->hasRoles('ROLE_ADMIN') || $intervention->getClient()->getUser()->getId() === $user->getId();
    }

    private function canCreate(UserInterface $user): bool
    {
        return $user->hasRoles('ROLE_USER') || $user->hasRoles('ROLE_ADMIN');
    }
}
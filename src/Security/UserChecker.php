<?php

namespace App\Security;

use App\Entity\Users;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof Users) {
            return;
        }

        // Si l'utilisateur n'est pas validé (isValid = false)
        if (!$user->isValid()) {
            throw new CustomUserMessageAccountStatusException('Votre compte n\'est pas encore validé par un administrateur.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Vous pouvez ajouter des vérifications supplémentaires après l'authentification si nécessaire
    }
}


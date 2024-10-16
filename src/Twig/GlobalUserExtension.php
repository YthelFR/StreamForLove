<?php

namespace App\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Entity\Users;

class GlobalUserExtension extends AbstractExtension
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_user', [$this, 'getCurrentUser']),
            new TwigFunction('pending_users_count', [$this, 'getPendingUsersCount']),
        ];
    }

    public function getCurrentUser()
    {
        return $this->security->getUser();
    }

    public function getPendingUsersCount(): int
    {
        $repository = $this->entityManager->getRepository(Users::class);
        return $repository->count(['isValid' => false]);
    }
}

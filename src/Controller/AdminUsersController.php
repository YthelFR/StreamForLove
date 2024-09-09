<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class AdminUsersController extends AbstractController
{
    #[Route('/pending', name: 'admin_user_pending')]
    public function pendingUsers(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les utilisateurs non activés
        $users = $entityManager->getRepository(Users::class)->findBy(['isActive' => false]);

        return $this->render('admin/user/pending.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/activate/{id}', name: 'admin_user_activate', methods: ['POST'])]
    public function activateUser(Users $user, EntityManagerInterface $entityManager): Response
    {
        // Activer l'utilisateur
        $user->setValid(true);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur activé avec succès.');

        return $this->redirectToRoute('admin_user_pending');
    }
}
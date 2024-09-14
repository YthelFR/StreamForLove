<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Form\ProfileType;
use App\Form\UsersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class AdminUsersController extends AbstractController
{
    
    #[Route('/', name: 'admin_users_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les utilisateurs
        $users = $entityManager->getRepository(Users::class)->findAll();

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/{id}/edit', name: 'admin_users_edit', methods: ['GET', 'POST'])]
    public function editUser(
        Users $user, 
        Request $request, 
        EntityManagerInterface $em, 
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du changement de mot de passe
            $newPassword = $form->get('new_password')->getData();
            if ($newPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Profil de l\'utilisateur mis à jour.');
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/users/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/admin/profile/edit', name: 'admin_profile_edit', methods: ['GET', 'POST'])]
    public function editAdminProfile(
        Request $request, 
        EntityManagerInterface $em, 
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Récupérer l'utilisateur connecté (administrateur)
        /** @var Users $admin */
        $admin = $this->getUser();

        $form = $this->createForm(ProfileType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du changement de mot de passe
            $newPassword = $form->get('new_password')->getData();
            if ($newPassword) {
                $hashedPassword = $passwordHasher->hashPassword($admin, $newPassword);
                $admin->setPassword($hashedPassword);
            }

            // Sauvegarde des changements
            $em->persist($admin);
            $em->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour.');
            return $this->redirectToRoute('admin_profile_edit');
        }

        return $this->render('admin/users/edit_profile.html.twig', [
            'form' => $form->createView(),
            'admin' => $admin,
        ]);
    }


    #[Route('/delete/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('admin_user_index');
    }

    #[Route('/pending', name: 'admin_user_pending')]
    public function pendingUsers(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les utilisateurs non activés
        $users = $entityManager->getRepository(Users::class)->findBy(['isValid' => false]);

        return $this->render('admin/users/pending.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/activate/{id}', name: 'admin_user_activate', methods: ['POST'])]
    public function activateUser(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('activate'.$user->getId(), $request->request->get('_token'))) {
            // Activer l'utilisateur
            $user->setValid(true);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur activé avec succès.');
        }

        return $this->redirectToRoute('admin_user_pending');
    }
}

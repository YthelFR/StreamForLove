<?php

namespace App\Controller\Streamer;

use App\Entity\Users;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/streamer')]
class StreamerUsersController extends AbstractController
{
    #[Route('/profil', name: 'streamer_profile_edit', methods: ['GET', 'POST'])]
    public function editProfile(
        Request $request, 
        EntityManagerInterface $em, 
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        /** @var Users $user */
        $user = $this->getUser();
        
        // Créez le formulaire pour éditer le profil
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du changement de mot de passe
            $oldPassword = $form->get('old_password')->getData();
            if ($oldPassword && $passwordHasher->isPasswordValid($user, $oldPassword)) {
                $newPassword = $form->get('new_password')->getData();
                if ($newPassword) {
                    $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                    $user->setPassword($hashedPassword);
                }
            }

            // Mise à jour des autres informations (email et pseudo)
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

            return $this->redirectToRoute('streamer_profile_edit');
        }

        return $this->render('dashboard/profile_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

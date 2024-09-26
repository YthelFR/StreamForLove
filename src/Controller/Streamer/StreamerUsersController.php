<?php

namespace App\Controller\Streamer;

use App\Entity\Users;
use App\Form\AvatarType;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/streamer')]
class StreamerUsersController extends AbstractController
{
    #[Route('/profil', name: 'streamer_profile_edit', methods: ['GET', 'POST'])]
    public function editProfile(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        SluggerInterface $slugger
    ): Response {
        /** @var Users $user */
        $user = $this->getUser();
    
        // Formulaire pour l'avatar
        $avatarForm = $this->createForm(AvatarType::class);
        $avatarForm->handleRequest($request);
    
        if ($avatarForm->isSubmitted() && $avatarForm->isValid()) {
            // Gestion du changement d'avatar
            $avatarFile = $avatarForm->get('avatar')->getData();
            if ($avatarFile) {
                // Supprimer l'ancien avatar s'il existe (sauf si c'est l'avatar par défaut)
                $oldAvatar = $user->getAvatar();
                if ($oldAvatar && $oldAvatar !== 'default-avatar.png') {
                    $oldAvatarPath = $this->getParameter('avatars_directory') . '/' . $oldAvatar;
                    if (file_exists($oldAvatarPath)) {
                        unlink($oldAvatarPath); // Supprimer l'ancien fichier avatar
                    }
                }
    
                // Générer un nouveau nom de fichier pour l'avatar
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();
    
                try {
                    // Déplacer le fichier téléchargé dans le répertoire des avatars
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                    // Mettre à jour l'avatar de l'utilisateur dans la base de données
                    $user->setAvatar($newFilename);
                    $em->flush();
                    $this->addFlash('success', 'Votre avatar a été mis à jour avec succès.');
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors du téléchargement de l\'avatar : ' . $e->getMessage());
                }
            }
        }
    
        // Formulaire pour le profil
        $profileForm = $this->createForm(ProfileType::class, $user);
        $profileForm->handleRequest($request);
    
        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            // Gestion du mot de passe s'il est modifié
            $oldPassword = $profileForm->get('old_password')->getData();
            $newPassword = $profileForm->get('new_password')->getData();
            if ($oldPassword && $newPassword) {
                if ($passwordHasher->isPasswordValid($user, $oldPassword)) {
                    $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                    $user->setPassword($hashedPassword);
                } else {
                    $this->addFlash('danger', 'Le mot de passe actuel est incorrect.');
                    return $this->redirectToRoute('streamer_profile_edit');
                }
            }
    
            $em->flush(); // Persist n'est pas nécessaire ici car l'entité $user est déjà gérée.
    
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
            return $this->redirectToRoute('streamer_profile_edit');
        }
    
        return $this->render('dashboard/profile_edit.html.twig', [
            'avatar_form' => $avatarForm->createView(),
            'profile_form' => $profileForm->createView(),
        ]);
    }
}

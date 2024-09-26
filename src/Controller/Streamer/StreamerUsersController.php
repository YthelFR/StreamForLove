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
    public function editAdminProfile(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        SluggerInterface $slugger
    ): Response {
        /** @var Users $admin */
        $admin = $this->getUser();
    
        // Formulaire pour l'Avatar
        $avatarForm = $this->createForm(AvatarType::class);
        $avatarForm->handleRequest($request);
    
        if ($avatarForm->isSubmitted() && $avatarForm->isValid()) {
            $avatarFile = $avatarForm->get('avatar')->getData();
            if ($avatarFile) {
                // Supprimer l'ancien avatar s'il existe (sauf si c'est l'avatar par défaut)
                $oldAvatar = $admin->getAvatar();
                if ($oldAvatar && $oldAvatar !== 'default-avatar.png') {
                    $oldAvatarPath = $this->getParameter('avatars_directory') . '/' . $oldAvatar;
                    if (file_exists($oldAvatarPath)) {
                        unlink($oldAvatarPath); // Supprimer le fichier
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
                    $admin->setAvatar($newFilename);
                    $em->flush();
                    $this->addFlash('success', 'Votre avatar a été mis à jour avec succès.');
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'avatar : ' . $e->getMessage());
                }
            }
        }
    
        // Formulaire pour le profil
        $form = $this->createForm(ProfileType::class, $admin);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('new_password')->getData();
            if ($newPassword) {
                $hashedPassword = $passwordHasher->hashPassword($admin, $newPassword);
                $admin->setPassword($hashedPassword);
            }
            $em->persist($admin);
            $em->flush();
    
            $this->addFlash('success', 'Votre profil a été mis à jour.');
            return $this->redirectToRoute('admin_profile_edit');
        }
    
        return $this->render('admin/users/edit_profile.html.twig', [
            'avatar_form' => $avatarForm->createView(),
            'form' => $form->createView(),
            'admin' => $admin,
        ]);
    }
}

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

#[Route('/dashboard')]
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

        // Récupérer la cagnotte de l'utilisateur
        $cagnotte = $user->getCagnottes()->first(); // Récupère la première cagnotte liée (peut être ajusté)
        $lienCagnotte = $cagnotte ? $cagnotte->getLien() : null;

        // Formulaire pour l'avatar
        $avatarForm = $this->createForm(AvatarType::class);
        $avatarForm->handleRequest($request);

        if ($avatarForm->isSubmitted() && $avatarForm->isValid()) {
            // Gestion du changement d'avatar (reste inchangé)
            $avatarFile = $avatarForm->get('avatar')->getData();
            if ($avatarFile) {
                // Gestion de l'avatar (comme avant)
                $oldAvatar = $user->getAvatar();
                if ($oldAvatar && $oldAvatar !== 'default-avatar.png') {
                    $oldAvatarPath = $this->getParameter('avatars_directory') . '/' . $oldAvatar;
                    if (file_exists($oldAvatarPath)) {
                        unlink($oldAvatarPath);
                    }
                }

                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();

                try {
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                    $user->setAvatar($newFilename);
                    $em->flush();
                    $this->addFlash('success', 'Votre avatar a été mis à jour avec succès.');
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors du téléchargement de l\'avatar : ' . $e->getMessage());
                }
            }

            $pronoms = $avatarForm->get('pronoms')->getData();
            if ($pronoms) {
                $user->setPronoms($pronoms);
                $em->flush();
                $this->addFlash('success', 'Vos pronoms ont été mis à jour avec succès.');
            }

            // Mise à jour du lien de la cagnotte
            $lien = $avatarForm->get('lien')->getData();
            if ($lien && $cagnotte) {
                $cagnotte->setLien($lien);
                $em->flush();
                $this->addFlash('success', 'Le lien de la cagnotte a été mis à jour avec succès.');
            }
        }

        $profileForm = $this->createForm(ProfileType::class, $user);
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
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

            $em->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
            return $this->redirectToRoute('streamer_profile_edit');
        }

        return $this->render('dashboard/streamers/profile_edit.html.twig', [
            'avatar_form' => $avatarForm->createView(),
            'profile_form' => $profileForm->createView(),
            'lien_cagnotte' => $lienCagnotte,
        ]);
    }
}

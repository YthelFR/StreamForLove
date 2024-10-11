<?php

namespace App\Controller\Admin;

use App\Entity\Cagnotte;
use App\Entity\Users;
use App\Form\AvatarType;
use App\Form\CagnotteUserType;
use App\Form\EditUsersType;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/user')]
class AdminUsersController extends AbstractController
{

    #[Route('/', name: 'admin_users_index')]
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $searchTerm = $request->query->get('search', '');

        $queryBuilder = $entityManager->getRepository(Users::class)->createQueryBuilder('u');

        if ($searchTerm) {
            $queryBuilder
                ->where('u.pseudo LIKE :search OR u.email LIKE :search')
                ->setParameter('search', '%' . $searchTerm . '%');
        }

        $query = $queryBuilder->getQuery();
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // page number
            25 // limit per page
        );

        return $this->render('dashboard/admin/users/index.html.twig', [
            'users' => $users,
            'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_users_edit', methods: ['GET', 'POST'])]
    public function editUser(
        Users $user,
        Request $request,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        $form = $this->createForm(EditUsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Changement de rôle
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);

            // Envoi d'e-mail pour réinitialiser le mot de passe
            if ($request->request->has('send_password_reset_email')) {
                $token = bin2hex(random_bytes(32));
                $user->setResetToken($token);
                $em->persist($user);
                $em->flush();

                // Préparez l'e-mail avec Symfony Mailer
                $email = (new Email())
                    ->from('support@streamforlove.coalitionplus.org')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation du mot de passe')
                    ->html(
                        $this->renderView(
                            'dashboard/admin/mail/reset_password.html.twig',
                            ['token' => $token, 'user' => $user] // Ajout de l'utilisateur ici
                        )
                    );

                $mailer->send($email);
                $this->addFlash('success', 'Un e-mail de réinitialisation du mot de passe a été envoyé à l\'utilisateur.');
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Profil de l\'utilisateur mis à jour.');
            return $this->redirectToRoute('admin_users_index');
        }

        foreach ($form->getErrors(true) as $error) {
            $this->addFlash('error', $error->getMessage());
        }

        return $this->render('dashboard/admin/users/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/profile-edit', name: 'admin_profile_edit', methods: ['GET', 'POST'])]
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
            // Gestion du changement d'avatar
            $avatarFile = $avatarForm->get('avatar')->getData();
            if ($avatarFile) {
                // Supprimer l'ancien avatar s'il existe (sauf si c'est l'avatar par défaut)
                $oldAvatar = $admin->getAvatar();
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
                    $admin->setAvatar($newFilename);
                    $em->flush();
                    $this->addFlash('success', 'Votre avatar a été mis à jour avec succès.');
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors du téléchargement de l\'avatar : ' . $e->getMessage());
                }
            }

            // Mettre à jour les pronoms si modifiés
            $pronoms = $avatarForm->get('pronoms')->getData();
            if ($pronoms) {
                $admin->setPronoms($pronoms);
                $em->flush();
                $this->addFlash('success', 'Vos pronoms ont été mis à jour avec succès.');
            }
        }

        $cagnotteForm = $this->createForm(CagnotteUserType::class);
        $cagnotteForm->handleRequest($request);

        if ($cagnotteForm->isSubmitted() && $cagnotteForm->isValid()) {
            $lienCagnotte = $cagnotteForm->get('lien')->getData();
            if ($lienCagnotte) {
                // Récupérez la première cagnotte existante ou créez-en une nouvelle
                $cagnotte = $admin->getCagnottes()->first() ?: new Cagnotte();

                if (!$cagnotte->getId()) {
                    $cagnotte->setUser($admin); // Associe la cagnotte à l'utilisateur
                    $cagnotte->setLien($lienCagnotte); // Ajoute le lien de la cagnotte
                    $em->persist($cagnotte); // Persiste la nouvelle cagnotte
                } else {
                    $cagnotte->setLien($lienCagnotte); // Met à jour le lien de la cagnotte existante
                }

                $em->flush(); // Valide les changements
                $this->addFlash('success', 'Cagnotte mise à jour avec succès.');
            }
        }

        // Formulaire pour le profil
        $profileForm = $this->createForm(ProfileType::class, $admin);
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            // Gestion du mot de passe s'il est modifié
            $oldPassword = $profileForm->get('old_password')->getData();
            $newPassword = $profileForm->get('new_password')->getData();
            if ($oldPassword && $newPassword) {
                if ($passwordHasher->isPasswordValid($admin, $oldPassword)) {
                    $hashedPassword = $passwordHasher->hashPassword($admin, $newPassword);
                    $admin->setPassword($hashedPassword);
                } else {
                    $this->addFlash('danger', 'Le mot de passe actuel est incorrect.');
                    return $this->redirectToRoute('admin_profile_edit');
                }
            }

            $em->flush(); // Pas besoin de persist, car l'entité est déjà gérée

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
            return $this->redirectToRoute('admin_profile_edit');
        }

        return $this->render('dashboard/admin/users/edit_profile.html.twig', [
            'avatar_form' => $avatarForm->createView(),
            'profile_form' => $profileForm->createView(),
            'cagnotte_form' => $cagnotteForm->createView(),
        ]);
    }


    #[Route('/delete/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('admin_users_index');
    }

    #[Route('/pending', name: 'admin_user_pending')]
    public function pendingUsers(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(Users::class)->findBy(['isValid' => false]);

        return $this->render('dashboard/admin/users/pending.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/activate/{id}', name: 'admin_user_activate', methods: ['POST'])]
    public function activateUser(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('activate' . $user->getId(), $request->request->get('_token'))) {
            $user->setValid(true);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur activé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('admin_user_pending');
    }
}

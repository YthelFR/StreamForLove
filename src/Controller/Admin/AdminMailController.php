<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MailType;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin/mail')]
class AdminMailController extends AbstractController
{
    private MailerService $mailerService;
    private EntityManagerInterface $entityManager; // Déclarez la variable pour EntityManager

    public function __construct(MailerService $mailerService, EntityManagerInterface $entityManager)
    {
        $this->mailerService = $mailerService;
        $this->entityManager = $entityManager; // Initialisez EntityManager
    }

    #[Route('', name: 'admin_mail')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(MailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Envoyer un mail selon le type de destinataire
            if ($data['recipientType'] === 'all') {
                // Récupérer les utilisateurs selon le rôle choisi
                if ($data['role'] === 'all') {
                    // Envoyer à tous les utilisateurs
                    $users = $this->entityManager->getRepository(Users::class)->findAll();
                } else {
                    // Récupérer les utilisateurs selon le rôle spécifique
                    $users = $this->entityManager->getRepository(Users::class)->findBy(['roles' => [$data['role']]]);
                }

                foreach ($users as $user) {
                    $this->mailerService->sendMail($user->getEmail(), $data['subject'], $data['message'], $data['senderName']);
                }
            } else {
                // Envoyer à l'utilisateur spécifique
                foreach ($data['recipient'] as $recipient) {
                    $this->mailerService->sendMail($recipient->getEmail(), $data['subject'], $data['message'], $data['senderName']);
                }
            }

            $this->addFlash('success', 'Email(s) envoyé(s) avec succès.');
            return $this->redirectToRoute('admin_mail');
        }

        return $this->render('dashboard/admin/mail/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

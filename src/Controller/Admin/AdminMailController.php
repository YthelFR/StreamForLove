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
    private EntityManagerInterface $entityManager; 

    public function __construct(MailerService $mailerService, EntityManagerInterface $entityManager)
    {
        $this->mailerService = $mailerService;
        $this->entityManager = $entityManager; 
    }

    #[Route('', name: 'admin_mail')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(MailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($data['recipientType'] === 'all') {
                if ($data['role'] === 'all') {
                    $users = $this->entityManager->getRepository(Users::class)->findAll();
                } else {
                    $users = $this->entityManager->getRepository(Users::class)->findBy(['roles' => [$data['role']]]);
                }

                foreach ($users as $user) {
                    $this->mailerService->sendMail($user->getEmail(), $data['subject'], $data['message'], $data['senderName']);
                }
            } else {
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

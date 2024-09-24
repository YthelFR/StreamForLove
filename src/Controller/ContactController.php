<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();

                $email = (new Email())
                    ->from($data['email'])
                    ->to('support@streamforlove.coalitionplus.org')
                    ->subject($data['subject'])
                    ->text($data['message'] . "\n\nDe : " . $data['name'] . " <" . $data['email'] . ">");

                $mailer->send($email);
                $this->addFlash('success', 'Votre message a été envoyé avec succès.');

                return $this->redirectToRoute('contact');
            } else {
                $this->addFlash('error', 'Une erreur est survenue. Veuillez vérifier votre saisie.');
            }
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
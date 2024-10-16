<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $supportEmail = (new TemplatedEmail())
                ->from('noreply@streamforlove.coalitionplus.org')
                ->replyTo($data['email']) 
                ->to('support@streamforlove.coalitionplus.org')  
                ->subject('Nouvelle demande de contact : ' . $data['subject'])
                ->htmlTemplate('contact/emails/support_email.html.twig')
                ->context([
                    'name' => $data['name'],
                    'user_email' => $data['email'],  
                    'subject' => $data['subject'],
                    'message' => $data['message'],
                ]);

            $mailer->send($supportEmail);

            $userEmail = (new TemplatedEmail())
                ->from('support@streamforlove.coalitionplus.org')
                ->to($data['email'])  
                ->subject('Confirmation de réception de votre message')
                ->htmlTemplate('contact/emails/user_confirmation.html.twig')
                ->context([
                    'name' => $data['name'],
                    'subject' => $data['subject'],
                    'message' => $data['message'],
                ]);

            $mailer->send($userEmail);

            $this->addFlash('success', 'Votre message a été envoyé avec succès et une confirmation vous a été envoyée.');

            return $this->redirectToRoute('contact');
        } elseif ($form->isSubmitted()) {
            $this->addFlash('error', 'Une erreur est survenue. Veuillez vérifier votre saisie.');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier) {}

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setCreatedAt(new \DateTimeImmutable()); // Optionnel, mais bonne pratique d'initialiser createdAt
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('support@streamforlove.coalitionplus.org', 'Support Stream For Love'))
                    ->to((string) $user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/emails/confirmation_email.html.twig')
            );

            $mailer->send((new TemplatedEmail())
                    ->from(new Address('noreply@streamforlove.coalitionplus.org', 'Support Stream For Love'))
                    ->to('support@streamforlove.coalitionplus.org')
                    ->subject('Nouvel utilisateur à vérifier')
                    ->htmlTemplate('registration/emails/new_user_notification.html.twig')
                    ->context(['user' => $user]) // Passer l'utilisateur à l'email
            );

            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $userId = $request->get('id');
        $user = $entityManager->getRepository(Users::class)->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        try {
            // Vérifiez l'email et activez le compte
            $this->emailVerifier->handleEmailConfirmation($request, $user);

            // Envoyer un email de confirmation à l'utilisateur
            $mailer->send((new TemplatedEmail())
                    ->from(new Address('support@streamforlove.coalitionplus.org', 'Support Stream For Love'))
                    ->to($user->getEmail())
                    ->subject('Votre adresse e-mail a été vérifiée')
                    ->htmlTemplate('registration/emails/verification_success.html.twig')
            );

            // Message flash
            $this->addFlash('success', 'Votre adresse e-mail a été vérifiée avec succès.');

            return $this->redirectToRoute('app_home'); // Redirigez vers la page d'accueil
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
            return $this->redirectToRoute('app_register');
        }
    }
}

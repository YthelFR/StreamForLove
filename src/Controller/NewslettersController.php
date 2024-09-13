<?php

namespace App\Controller;

use App\Entity\Abonnes;
use App\Form\AbonnesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/newsletter')]
class NewslettersController extends AbstractController
{
    #[Route('/', name: 'app_newsletter_subscribe', methods: ['GET', 'POST'])]
    public function subscribe(Request $request, EntityManagerInterface $entityManager): Response
    {
        $abonne = new Abonnes();
        $form = $this->createForm(AbonnesType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($abonne);
            $entityManager->flush();

            $this->addFlash('success', 'Vous êtes inscrit avec succès à notre newsletter.');

            return $this->redirectToRoute('app_newsletter_subscribe');
        }

        return $this->render('newsletters/subscribe.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}


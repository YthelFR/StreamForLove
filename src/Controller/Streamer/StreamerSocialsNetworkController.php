<?php

namespace App\Controller\Streamer;

use App\Entity\SocialsNetwork;
use App\Form\SocialsNetworkType;
use App\Repository\SocialsNetworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard/socials')]
#[IsGranted('ROLE_STREAMER_ACTIF', 'ROLE_STREAMER_ABSENTS', 'ROLE_ADMIN', 'ROLE_MANAGER')]
final class StreamerSocialsNetworkController extends AbstractController
{
    #[Route(name: 'app_socials_network_index', methods: ['GET'])]
    public function index(SocialsNetworkRepository $socialsNetworkRepository): Response
    {
        return $this->render('streamer/socials_network/index.html.twig', [  // Mise à jour du chemin
            'socials_networks' => $socialsNetworkRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_socials_network_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $socialsNetwork = new SocialsNetwork();
        $form = $this->createForm(SocialsNetworkType::class, $socialsNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($socialsNetwork);
            $entityManager->flush();

            return $this->redirectToRoute('app_socials_network_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('streamer/socials_network/new.html.twig', [  // Mise à jour du chemin
            'socials_network' => $socialsNetwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_socials_network_show', methods: ['GET'])]
    public function show(SocialsNetwork $socialsNetwork): Response
    {
        return $this->render('streamer/socials_network/show.html.twig', [  // Mise à jour du chemin
            'socials_network' => $socialsNetwork,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_socials_network_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SocialsNetwork $socialsNetwork, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SocialsNetworkType::class, $socialsNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_socials_network_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('streamer/socials_network/edit.html.twig', [  // Mise à jour du chemin
            'socials_network' => $socialsNetwork,
            'form' => $form,
        ]);
    }
}

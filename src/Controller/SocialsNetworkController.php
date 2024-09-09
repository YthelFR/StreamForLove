<?php

namespace App\Controller;

use App\Entity\SocialsNetwork;
use App\Form\SocialsNetworkType;
use App\Repository\SocialsNetworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/socials/network')]
final class SocialsNetworkController extends AbstractController
{
    #[Route(name: 'app_socials_network_index', methods: ['GET'])]
    public function index(SocialsNetworkRepository $socialsNetworkRepository): Response
    {
        return $this->render('socials_network/index.html.twig', [
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

        return $this->render('socials_network/new.html.twig', [
            'socials_network' => $socialsNetwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_socials_network_show', methods: ['GET'])]
    public function show(SocialsNetwork $socialsNetwork): Response
    {
        return $this->render('socials_network/show.html.twig', [
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

        return $this->render('socials_network/edit.html.twig', [
            'socials_network' => $socialsNetwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_socials_network_delete', methods: ['POST'])]
    public function delete(Request $request, SocialsNetwork $socialsNetwork, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$socialsNetwork->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($socialsNetwork);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_socials_network_index', [], Response::HTTP_SEE_OTHER);
    }
}

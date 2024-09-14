<?php

namespace App\Controller\Streamer;

use App\Entity\SocialsNetwork;
use App\Form\SocialsNetworkType;
use App\Repository\SocialsNetworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/socials')]
final class StreamerSocialsNetworkController extends AbstractController
{
    private $security;

    public function __construct(SecurityBundleSecurity $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'streamer_socials_network_index', methods: ['GET'])]
    public function index(SocialsNetworkRepository $socialsNetworkRepository): Response
    {
        $user = $this->security->getUser();
        $socialsNetworks = $socialsNetworkRepository->findBy(['user' => $user]);

        return $this->render('dashboard/socials_network/index.html.twig', [
            'socials_networks' => $socialsNetworks,
        ]);
    }

    #[Route('/new', name: 'streamer_socials_network_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        $socialsNetwork = new SocialsNetwork();
        $socialsNetwork->setUser($user);

        $form = $this->createForm(SocialsNetworkType::class, $socialsNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($socialsNetwork);
            $entityManager->flush();

            return $this->redirectToRoute('streamer_socials_network_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/socials_network/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'streamer_socials_network_show', methods: ['GET'])]
    public function show(SocialsNetwork $socialsNetwork): Response
    {
        // Check if the logged-in user owns this network
        if ($socialsNetwork->getUser() !== $this->security->getUser()) {
            throw $this->createAccessDeniedException('You do not have permission to view this social network.');
        }

        return $this->render('dashboard/socials_network/show.html.twig', [
            'socials_network' => $socialsNetwork,
        ]);
    }

    #[Route('/{id}/edit', name: 'streamer_socials_network_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SocialsNetwork $socialsNetwork, EntityManagerInterface $entityManager): Response
    {
        // Check if the logged-in user owns this network
        if ($socialsNetwork->getUser() !== $this->security->getUser()) {
            throw $this->createAccessDeniedException('You do not have permission to edit this social network.');
        }

        $form = $this->createForm(SocialsNetworkType::class, $socialsNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('streamer_socials_network_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/socials_network/edit.html.twig', [
            'form' => $form->createView(),
            'socials_network' => $socialsNetwork,
        ]);
    }

    #[Route('/{id}/delete', name: 'streamer_socials_network_delete', methods: ['POST'])]
    public function delete(Request $request, SocialsNetwork $socialsNetwork, EntityManagerInterface $entityManager): Response
    {
        // Check if the logged-in user owns this network
        if ($socialsNetwork->getUser() !== $this->security->getUser()) {
            throw $this->createAccessDeniedException('You do not have permission to delete this social network.');
        }

        if ($this->isCsrfTokenValid('delete' . $socialsNetwork->getId(), $request->request->get('_token'))) {
            $entityManager->remove($socialsNetwork);
            $entityManager->flush();
        }

        return $this->redirectToRoute('streamer_socials_network_index', [], Response::HTTP_SEE_OTHER);
    }
}

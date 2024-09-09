<?php

namespace App\Controller;

use App\Entity\Abonnes;
use App\Form\AbonnesType;
use App\Repository\AbonnesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/abonnes')]
final class AbonnesController extends AbstractController
{
    #[Route(name: 'app_abonnes_index', methods: ['GET'])]
    public function index(AbonnesRepository $abonnesRepository): Response
    {
        return $this->render('abonnes/index.html.twig', [
            'abonnes' => $abonnesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_abonnes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $abonne = new Abonnes();
        $form = $this->createForm(AbonnesType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($abonne);
            $entityManager->flush();

            return $this->redirectToRoute('app_abonnes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('abonnes/new.html.twig', [
            'abonne' => $abonne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_abonnes_show', methods: ['GET'])]
    public function show(Abonnes $abonne): Response
    {
        return $this->render('abonnes/show.html.twig', [
            'abonne' => $abonne,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_abonnes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Abonnes $abonne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AbonnesType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_abonnes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('abonnes/edit.html.twig', [
            'abonne' => $abonne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_abonnes_delete', methods: ['POST'])]
    public function delete(Request $request, Abonnes $abonne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonne->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($abonne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_abonnes_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\Presentations;
use App\Form\PresentationsType;
use App\Repository\PresentationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/presentations')]
final class PresentationsController extends AbstractController
{
    #[Route(name: 'app_presentations_index', methods: ['GET'])]
    public function index(PresentationsRepository $presentationsRepository): Response
    {
        return $this->render('presentations/index.html.twig', [
            'presentations' => $presentationsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_presentations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $presentation = new Presentations();
        $form = $this->createForm(PresentationsType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($presentation);
            $entityManager->flush();

            return $this->redirectToRoute('app_presentations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('presentations/new.html.twig', [
            'presentation' => $presentation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_presentations_show', methods: ['GET'])]
    public function show(Presentations $presentation): Response
    {
        return $this->render('presentations/show.html.twig', [
            'presentation' => $presentation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_presentations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Presentations $presentation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PresentationsType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_presentations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('presentations/edit.html.twig', [
            'presentation' => $presentation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_presentations_delete', methods: ['POST'])]
    public function delete(Request $request, Presentations $presentation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$presentation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($presentation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_presentations_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\Outsiders;
use App\Form\OutsidersType;
use App\Repository\OutsidersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/outsiders')]
final class OutsidersController extends AbstractController
{
    #[Route(name: 'app_outsiders_index', methods: ['GET'])]
    public function index(OutsidersRepository $outsidersRepository): Response
    {
        return $this->render('outsiders/index.html.twig', [
            'outsiders' => $outsidersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_outsiders_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $outsider = new Outsiders();
        $form = $this->createForm(OutsidersType::class, $outsider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($outsider);
            $entityManager->flush();

            return $this->redirectToRoute('app_outsiders_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('outsiders/new.html.twig', [
            'outsider' => $outsider,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_outsiders_show', methods: ['GET'])]
    public function show(Outsiders $outsider): Response
    {
        return $this->render('outsiders/show.html.twig', [
            'outsider' => $outsider,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_outsiders_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Outsiders $outsider, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OutsidersType::class, $outsider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_outsiders_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('outsiders/edit.html.twig', [
            'outsider' => $outsider,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_outsiders_delete', methods: ['POST'])]
    public function delete(Request $request, Outsiders $outsider, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$outsider->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($outsider);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_outsiders_index', [], Response::HTTP_SEE_OTHER);
    }
}

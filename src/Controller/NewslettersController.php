<?php

namespace App\Controller;

use App\Entity\Newsletters;
use App\Form\NewslettersType;
use App\Repository\NewslettersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/newsletters')]
final class NewslettersController extends AbstractController
{
    #[Route(name: 'app_newsletters_index', methods: ['GET'])]
    public function index(NewslettersRepository $newslettersRepository): Response
    {
        return $this->render('newsletters/index.html.twig', [
            'newsletters' => $newslettersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_newsletters_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $newsletter = new Newsletters();
        $form = $this->createForm(NewslettersType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($newsletter);
            $entityManager->flush();

            return $this->redirectToRoute('app_newsletters_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('newsletters/new.html.twig', [
            'newsletter' => $newsletter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_newsletters_show', methods: ['GET'])]
    public function show(Newsletters $newsletter): Response
    {
        return $this->render('newsletters/show.html.twig', [
            'newsletter' => $newsletter,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_newsletters_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Newsletters $newsletter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NewslettersType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_newsletters_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('newsletters/edit.html.twig', [
            'newsletter' => $newsletter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_newsletters_delete', methods: ['POST'])]
    public function delete(Request $request, Newsletters $newsletter, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$newsletter->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($newsletter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_newsletters_index', [], Response::HTTP_SEE_OTHER);
    }
}

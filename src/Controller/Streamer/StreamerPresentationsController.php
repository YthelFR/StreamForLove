<?php

namespace App\Controller\Streamer;

use App\Entity\Presentations;
use App\Form\PresentationsType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard/presentations')]
#[IsGranted('ROLE_STREAMER_ACTIF')]
#[IsGranted('ROLE_STREAMER_ABSENT')]
class StreamerPresentationsController extends AbstractController
{
    private $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }
    #[Route('/', name: 'streamer_presentations_index', methods: ['GET'])]
    public function index(): Response
    {
        $presentation = $this->usersRepository->getUsers()->getStreamersPresentation();

        return $this->render('streamer/presentations/index.html.twig', [
            'presentation' => $presentation,
        ]);
    }


    #[Route('/new', name: 'streamer_presentations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $presentation = new Presentations();
        $presentation->setStreamersPresentation($this->getUser());

        $form = $this->createForm(PresentationsType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($presentation);
            $entityManager->flush();

            return $this->redirectToRoute('streamer_presentations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('streamer/presentations/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'streamer_presentations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Presentations $presentation, EntityManagerInterface $entityManager): Response
    {
        // Vérification que l'utilisateur connecté est bien propriétaire de la présentation
        if ($presentation->getStreamersPresentation() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas la permission de modifier cette présentation.');
        }

        $form = $this->createForm(PresentationsType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('streamer_presentations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('streamer/presentations/edit.html.twig', [
            'form' => $form->createView(),
            'presentation' => $presentation,
        ]);
    }

    #[Route('/{id}', name: 'streamer_presentations_delete', methods: ['POST'])]
    public function delete(Request $request, Presentations $presentation, EntityManagerInterface $entityManager): Response
    {
        if ($presentation->getStreamersPresentation() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas la permission de supprimer cette présentation.');
        }

        if ($this->isCsrfTokenValid('delete' . $presentation->getId(), $request->get('_token'))) {
            $entityManager->remove($presentation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('streamer_presentations_index', [], Response::HTTP_SEE_OTHER);
    }
}

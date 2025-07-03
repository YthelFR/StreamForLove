<?php

namespace App\Controller\Admin;

use App\Entity\EvenementsClips;
use App\Entity\Evenements;
use App\Form\EvenementsClipsType;
use App\Repository\EvenementsClipsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/clips')]
class AdminClipsEventsController extends AbstractController
{
    private EvenementsClipsRepository $clipsRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EvenementsClipsRepository $clipsRepository, EntityManagerInterface $entityManager)
    {
        $this->clipsRepository = $clipsRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'admin_clips_index', methods: ['GET'])]
    public function index(): Response
    {
        $clips = $this->clipsRepository->findAll();

        return $this->render('dashboard/admin/clips/index.html.twig', [
            'clips' => $clips,
        ]);
    }

    #[Route('/new', name: 'admin_clips_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $clip = new EvenementsClips();
        $form = $this->createForm(EvenementsClipsType::class, $clip);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($clip);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_clips_index');
        }

        return $this->render('dashboard/admin/clips/new.html.twig', [
            'clip' => $clip,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_clips_show', methods: ['GET'])]
    public function show(EvenementsClips $clip): Response
    {
        return $this->render('dashboard/admin/clips/show.html.twig', [
            'clip' => $clip,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_clips_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EvenementsClips $clip): Response
    {
        $form = $this->createForm(EvenementsClipsType::class, $clip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_clips_index');
        }

        return $this->render('dashboard/admin/clips/edit.html.twig', [
            'clip' => $clip,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_clips_delete', methods: ['POST'])]
    public function delete(Request $request, EvenementsClips $clip): Response
    {
        if ($this->isCsrfTokenValid('delete' . $clip->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($clip);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('admin_clips_index');
    }
}

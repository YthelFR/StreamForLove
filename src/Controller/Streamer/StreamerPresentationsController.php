<?php

namespace App\Controller\Streamer;

use App\Entity\Presentations;
use App\Form\PresentationsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/dashboard/presentations')]
class StreamerPresentationsController extends AbstractController
{
    #[Route('/', name: 'streamer_presentations_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $presentation = $entityManager->getRepository(Presentations::class)->findOneBy([
            'streamersPresentation' => $user,
        ]);

        return $this->render('dashboard/streamers/presentations/index.html.twig', [
            'presentation' => $presentation,
        ]);
    }

    #[Route('/new', name: 'streamer_presentations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $presentation = new Presentations();
        $presentation->setStreamersPresentation($user);

        $form = $this->createForm(PresentationsType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleFileUpload($form, $presentation, $slugger);

            $entityManager->persist($presentation);
            $entityManager->flush();

            $this->addFlash('success', 'Présentation créée avec succès.');
            return $this->redirectToRoute('streamer_presentations_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted()) {
            $this->addFlash('error', 'Une erreur est survenue lors de la création de la présentation.');
        }

        return $this->render('dashboard/streamers/presentations/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'streamer_presentations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Presentations $presentation, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

        if ($presentation->getStreamersPresentation() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas la permission de modifier cette présentation.');
        }

        $form = $this->createForm(PresentationsType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleFileUpload($form, $presentation, $slugger, true);

            $entityManager->flush();

            $this->addFlash('success', 'Présentation modifiée avec succès.');
            return $this->redirectToRoute('streamer_presentations_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted()) {
            $this->addFlash('error', 'Une erreur est survenue lors de la modification de la présentation.');
        }

        return $this->render('dashboard/streamers/presentations/edit.html.twig', [
            'form' => $form->createView(),
            'presentation' => $presentation,
        ]);
    }

    private function handleFileUpload($form, $presentation, SluggerInterface $slugger, $isEdit = false): void
    {
        $file = $form->get('picturePath')->getData();
        if ($file) {
            if ($isEdit && $presentation->getPicturePath()) {
                $this->deleteFile($this->getParameter('upload_directory') . '/' . $presentation->getPicturePath());
            }
            $newFilename = $this->uploadFile($file, 'upload_directory', $slugger);
            $presentation->setPicturePath($newFilename);
        }

        $planningFile = $form->get('planning')->getData();
        if ($planningFile) {
            if ($isEdit && $presentation->getPlanning()) {
                $this->deleteFile($this->getParameter('planning_directory') . '/' . $presentation->getPlanning());
            }
            $newFilename = $this->uploadFile($planningFile, 'planning_directory', $slugger);
            $presentation->setPlanning($newFilename);
        }

        $goalsFile = $form->get('goals')->getData();
        if ($goalsFile) {
            if ($isEdit && $presentation->getGoals()) {
                $this->deleteFile($this->getParameter('goals_directory') . '/' . $presentation->getGoals());
            }
            $newFilename = $this->uploadFile($goalsFile, 'goals_directory', $slugger);
            $presentation->setGoals($newFilename);
        }
    }

    private function uploadFile($file, $directoryParameter, SluggerInterface $slugger): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move(
            $this->getParameter($directoryParameter),
            $newFilename
        );

        return $newFilename;
    }

    private function deleteFile($filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
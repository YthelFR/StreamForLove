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

        return $this->render('dashboard/presentations/index.html.twig', [
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
            $file = $form->get('picturePath')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('kernel.project_dir') . '/public/assets/users/presentations/pictures',
                    $newFilename
                );

                $presentation->setPicturePath($newFilename); // Set the path to the file
            } else {
                $presentation->setPicturePath(null); // Ensure picturePath is null if no file is uploaded
            }

            $planningFile = $form->get('planning')->getData();
            if ($planningFile) {
                $originalFilename = pathinfo($planningFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $planningFile->guessExtension();

                $planningFile->move(
                    $this->getParameter('planning_directory') . '/public/assets/users/presentations/planning',
                    $newFilename
                );
                $presentation->setPlanning($newFilename);
            } else {
                $presentation->setPlanning(null);
            }

            $goalsFile = $form->get('goals')->getData();
            if ($goalsFile) {
                $originalFilename = pathinfo($goalsFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $goalsFile->guessExtension();

                $goalsFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/assets/users/presentations/goals',
                    $newFilename
                );
                $presentation->setGoals($newFilename);
            } else {
                $presentation->setGoals(null);
            }

            
            $entityManager->persist($presentation);
            $entityManager->flush();

            return $this->redirectToRoute('streamer_presentations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/presentations/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'streamer_presentations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Presentations $presentation, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

        // Vérifiez que la présentation appartient bien au streamer connecté
        if ($presentation->getStreamersPresentation() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas la permission de modifier cette présentation.');
        }

        $form = $this->createForm(PresentationsType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('picturePath')->getData();
            $planningFile = $form->get('planning')->getData(); // Ajout de la gestion du planning

            if ($file) {
                if ($presentation->getPicturePath()) {
                    $oldPicturePath = $this->getParameter('kernel.project_dir') . '/public/assets/users/presentations/pictures/' . $presentation->getPicturePath();
                    if (file_exists($oldPicturePath)) {
                        unlink($oldPicturePath);
                    }
                }

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('kernel.project_dir') . '/public/assets/users/presentations/pictures',
                    $newFilename
                );

                $presentation->setPicturePath($newFilename); // Set the new file path
            }

            if ($planningFile) { // Gestion du fichier planning
                if ($presentation->getPlanning()) {
                    $oldPlanningPath = $this->getParameter('planning_directory') . '/' . $presentation->getPlanning();
                    if (file_exists($oldPlanningPath)) {
                        unlink($oldPlanningPath);
                    }
                }
        
                $originalPlanningFilename = pathinfo($planningFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safePlanningFilename = $slugger->slug($originalPlanningFilename);
                $newPlanningFilename = $safePlanningFilename . '-' . uniqid() . '.' . $planningFile->guessExtension();
        
                $planningFile->move(
                    $this->getParameter('planning_directory'),
                    $newPlanningFilename
                );
        
                $presentation->setPlanning($newPlanningFilename); // Set the new planning file path
            }

            $goalsFile = $form->get('goals')->getData();
            if ($goalsFile) {
                if ($presentation->getGoals()) {
                    $oldGoalsPath = $this->getParameter('kernel.project_dir') . '/public/assets/users/presentations/goals/' . $presentation->getGoals();
                    if (file_exists($oldGoalsPath)) {
                        unlink($oldGoalsPath);
                    }
                }

                $originalGoalsFilename = pathinfo($goalsFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeGoalsFilename = $slugger->slug($originalGoalsFilename);
                $newGoalsFilename = $safeGoalsFilename . '-' . uniqid() . '.' . $goalsFile->guessExtension();

                $goalsFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/assets/users/presentations/goals',
                    $newGoalsFilename
                );

                $presentation->setGoals($newGoalsFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('streamer_presentations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/presentations/edit.html.twig', [
            'form' => $form->createView(),
            'presentation' => $presentation,
        ]);
    }
}

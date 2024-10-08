<?php

namespace App\Controller\Admin;

use App\Entity\Presentations;
use App\Form\PresentationsType;
use App\Repository\PresentationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/presentations')]
class AdminPresentationsController extends AbstractController
{
    #[Route('/', name: 'admin_presentations_index', methods: ['GET'])]
    public function index(PresentationsRepository $presentationsRepository): Response
    {
        return $this->render('dashboard/admin/presentations/index.html.twig', [
            'presentations' => $presentationsRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_presentations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Presentations $presentation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PresentationsType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picturePath')->getData();
            if ($pictureFile) {
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('upload_directory'),
                    $newFilename
                );
                $presentation->setPicturePath($newFilename);
            }

            $planningFile = $form->get('planning')->getData();
            if ($planningFile) {
                $newPlanningFilename = uniqid() . '.' . $planningFile->guessExtension();
                $planningFile->move(
                    $this->getParameter('planning_directory'),
                    $newPlanningFilename
                );
                $presentation->setPlanning($newPlanningFilename);
            }

            $goalsFile = $form->get('goals')->getData();
            if ($goalsFile) {
                $newGoalsFilename = uniqid() . '.' . $goalsFile->guessExtension();
                $goalsFile->move(
                    $this->getParameter('goals_directory'),
                    $newGoalsFilename
                );
                $presentation->setGoals($newGoalsFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Les modifications ont été enregistrées avec succès.');

            return $this->redirectToRoute('admin_presentations_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Veuillez corriger les erreurs dans le formulaire.');
        }

        return $this->render('dashboard/admin/presentations/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_presentations_delete', methods: ['POST'])]
    public function delete(Request $request, Presentations $presentation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $presentation->getId(), $request->get('_token'))) {
            $entityManager->remove($presentation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_presentations_index', [], Response::HTTP_SEE_OTHER);
    }
}

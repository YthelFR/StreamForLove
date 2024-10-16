<?php

namespace App\Controller\Admin;

use App\Entity\Evenements;
use App\Form\EvenementsType;
use App\Repository\EvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEvenementsController extends AbstractController
{
    #[Route('/admin/evenements', name: 'admin_evenements_index', methods: ['GET'])]
    public function index(EvenementsRepository $evenementsRepository): Response
    {
        $evenements = $evenementsRepository->findAll();

        return $this->render('dashboard/admin/evenements/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/admin/evenements/new', name: 'admin_evenements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnailFile = $form->get('thumbnail')->getData();

            if ($thumbnailFile) {
                $newFilename = uniqid() . '.' . $thumbnailFile->guessExtension();

                try {
                    $thumbnailFile->move(
                        $this->getParameter('evenements_directory'),
                        $newFilename
                    );
                    $evenement->setThumbnail($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                }
            }

            $entityManager->persist($evenement);
            $entityManager->flush();

            $this->addFlash('success', 'L\'événement a été créé avec succès.');

            return $this->redirectToRoute('admin_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/admin/evenements/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/evenements/{id}', name: 'admin_evenements_show', methods: ['GET'])]
    public function show(Evenements $evenement): Response
    {
        $participants = $evenement->getParticipants(); 

        return $this->render('dashboard/admin/evenements/show.html.twig', [
            'evenement' => $evenement,
            'participants' => $participants, 
        ]);
    }

    #[Route('/admin/evenements/{id}/edit', name: 'admin_evenements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnailFile = $form->get('thumbnail')->getData();

            if ($thumbnailFile) {
                $newFilename = uniqid() . '.' . $thumbnailFile->guessExtension();

                try {
                    $thumbnailFile->move(
                        $this->getParameter('evenements_directory'),
                        $newFilename
                    );
                    $evenement->setThumbnail($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                }
            } else {
                $newFilename = $evenement->getThumbnail(); 
            }
            $entityManager->persist($evenement);
            $entityManager->flush();

            $this->addFlash('success', 'L\'événement a été mis à jour avec succès.');

            return $this->redirectToRoute('admin_evenements_index');
        }

        return $this->render('dashboard/admin/evenements/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/evenements/{id}', name: 'admin_evenements_delete', methods: ['POST'])]
    public function delete(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evenement->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($evenement);
                $entityManager->flush();

                $this->addFlash('success', 'L\'événement a été supprimé avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la suppression de l\'événement. Vérifiez votre demande.');
            }
        } else {
            $this->addFlash('error', 'Erreur CSRF lors de la suppression de l\'événement.');
        }

        return $this->redirectToRoute('admin_evenements_index', [], Response::HTTP_SEE_OTHER);
    }
}

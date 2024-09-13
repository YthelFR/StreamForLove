<?php

namespace App\Controller\Admin;

use App\Entity\Evenements;
use App\Form\EvenementsType;
use App\Repository\EvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted as AttributeIsGranted;

#[Route('/admin/evenements')]
#[AttributeIsGranted('ROLE_ADMIN')] // Restreindre l'accès à ce contrôleur aux utilisateurs ayant le rôle ADMIN
final class AdminEvenementsController extends AbstractController
{
    #[Route('/new', name: 'admin_evenements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/evenements/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_evenements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/evenements/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_evenements_delete', methods: ['POST'])]
    public function delete(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
    }
}
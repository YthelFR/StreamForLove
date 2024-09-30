<?php

namespace App\Controller\Admin;

use App\Entity\Outsiders;
use App\Form\OutsidersType;
use App\Repository\OutsidersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/outsiders')]
class AdminOutsidersController extends AbstractController
{
    #[Route('/', name: 'admin_outsiders_index', methods: ['GET'])]
    public function index(OutsidersRepository $outsidersRepository): Response
    {
        return $this->render('dashboard/admin/outsiders/index.html.twig', [
            'outsiders' => $outsidersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_outsiders_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $outsider = new Outsiders();
        $form = $this->createForm(OutsidersType::class, $outsider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $outsider->setUser($this->getUser());

            $entityManager->persist($outsider);
            $entityManager->flush();

            $this->addFlash('success', 'L\'outsider a bien été créé.');

            return $this->redirectToRoute('admin_outsiders_show', ['id' => $outsider->getId()]);
        }

        return $this->render('dashboard/admin/outsiders/new.html.twig', [
            'outsider' => $outsider,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_outsiders_show', methods: ['GET'])]
    public function show(OutsidersRepository $outsidersRepository, int $id): Response
    {
        $outsider = $outsidersRepository->find($id);

        if (!$outsider) {
            throw $this->createNotFoundException('Outsider non trouvé');
        }

        return $this->render('dashboard/admin/outsiders/show.html.twig', [
            'outsider' => $outsider,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_outsiders_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Outsiders $outsider, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OutsidersType::class, $outsider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'L\'outsider a été mis à jour avec succès.');

            return $this->redirectToRoute('admin_outsiders_show', ['id' => $outsider->getId()]);
        }

        return $this->render('dashboard/admin/outsiders/edit.html.twig', [
            'form' => $form->createView(),
            'outsider' => $outsider,
        ]);
    }

    #[Route('/{id}', name: 'admin_outsiders_delete', methods: ['POST'])]
    public function delete(Request $request, Outsiders $outsider, EntityManagerInterface $entityManager): Response
    {
        // Vérification du token CSRF
        if ($this->isCsrfTokenValid('delete' . $outsider->getId(), $request->request->get('_token'))) {
            $entityManager->remove($outsider);
            $entityManager->flush();

            $this->addFlash('success', 'L\'outsider a bien été supprimé.');
        } else {
            $this->addFlash('error', 'Échec de la suppression. Le token CSRF est invalide.');
            return $this->redirectToRoute('admin_outsiders_index');
        }

        return $this->redirectToRoute('admin_outsiders_index');
    }
}

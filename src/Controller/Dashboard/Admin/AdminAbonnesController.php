<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\Abonnes;
use App\Form\AbonnesType;
use App\Form\AbonneType;
use App\Repository\AbonnesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/abonnes')]
class AdminAbonnesController extends AbstractController
{
    #[Route('/', name: 'admin_abonnes_index', methods: ['GET'])]
    public function index(AbonnesRepository $abonnesRepository): Response
    {
        return $this->render('admin/abonnes/index.html.twig', [
            'abonnes' => $abonnesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_abonnes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $abonne = new Abonnes();
        $form = $this->createForm(AbonnesType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($abonne);
            $entityManager->flush();

            return $this->redirectToRoute('admin_abonnes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/abonnes/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_abonnes_show', methods: ['GET'])]
    public function show(Abonnes $abonne): Response
    {
        return $this->render('admin/abonnes/show.html.twig', [
            'abonne' => $abonne,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_abonnes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Abonnes $abonne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AbonnesType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_abonnes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/abonnes/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_abonnes_delete', methods: ['POST'])]
    public function delete(Request $request, Abonnes $abonne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $abonne->getId(), $request->request->get('_token'))) {
            $entityManager->remove($abonne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_abonnes_index', [], Response::HTTP_SEE_OTHER);
    }
}

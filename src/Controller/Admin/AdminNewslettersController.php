<?php

namespace App\Controller\Admin;

use App\Entity\Newsletters;
use App\Form\NewslettersType;
use App\Repository\NewslettersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/newsletters')]
class AdminNewslettersController extends AbstractController
{
    #[Route('/', name: 'admin_newsletters_index', methods: ['GET'])]
    public function index(NewslettersRepository $newslettersRepository): Response
    {
        return $this->render('dashboard/admin/newsletters/index.html.twig', [
            'newsletters' => $newslettersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_newsletters_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $newsletter = new Newsletters();
        $form = $this->createForm(NewslettersType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($newsletter);
            $entityManager->flush();

            $this->addFlash('success', 'La newsletter a été créée avec succès.'); 

            return $this->redirectToRoute('admin_newsletters_index');
        }

        return $this->render('dashboard/admin/newsletters/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_newsletters_show', methods: ['GET'])]
    public function show(Newsletters $newsletter): Response
    {
        return $this->render('dashboard/admin/newsletters/show.html.twig', [
            'newsletter' => $newsletter,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_newsletters_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Newsletters $newsletter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NewslettersType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La newsletter a été mise à jour avec succès.'); 

            return $this->redirectToRoute('admin_newsletters_index');
        }

        return $this->render('dashboard/admin/newsletters/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_newsletters_delete', methods: ['POST'])]
    public function delete(Request $request, Newsletters $newsletter, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $newsletter->getId(), $request->request->get('_token'))) {
            $entityManager->remove($newsletter);
            $entityManager->flush();

            $this->addFlash('success', 'La newsletter a été supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Erreur de suppression de la newsletter.');
        }

        return $this->redirectToRoute('admin_newsletters_index');
    }
}

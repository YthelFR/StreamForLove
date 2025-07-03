<?php

namespace App\Controller\Admin;

use App\Entity\Cagnotte;
use App\Entity\Users;
use App\Form\CagnotteType;
use App\Repository\CagnotteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/cagnottes')]
class AdminCagnottesController extends AbstractController
{
    #[Route('/', name: 'admin_cagnottes_index', methods: ['GET'])]
    public function index(CagnotteRepository $cagnotteRepository): Response
    {
        return $this->render('dashboard/admin/cagnottes/index.html.twig', [
            'cagnottes' => $cagnotteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_cagnottes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cagnotte = new Cagnotte();
        $form = $this->createForm(CagnotteType::class, $cagnotte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->get('user')->getData(); 
            if ($user) {
                $cagnotte->setUser($user); 
                $entityManager->persist($cagnotte);
                $entityManager->flush();

                return $this->redirectToRoute('admin_cagnottes_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('dashboard/admin/cagnottes/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_cagnottes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cagnotte $cagnotte, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CagnotteType::class, $cagnotte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_cagnottes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/admin/cagnottes/edit.html.twig', [
            'form' => $form->createView(),
            'cagnotte' => $cagnotte, 
        ]);
    }

    #[Route('/delete-all', name: 'admin_cagnottes_delete_all', methods: ['POST'])]
    public function deleteAll(CagnotteRepository $cagnotteRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $cagnottes = $cagnotteRepository->findAll();
        foreach ($cagnottes as $cagnotte) {
            $entityManager->remove($cagnotte);
        }

        $entityManager->flush();
        $this->addFlash('success', 'Toutes les cagnottes ont été supprimées avec succès.');

        return $this->redirectToRoute('admin_cagnottes_index');
    }
}
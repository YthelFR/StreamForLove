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
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/presentations')]
#[IsGranted('ROLE_ADMIN')]
#[IsGranted('ROLE_MANAGER')]
class AdminPresentationsController extends AbstractController
{
    #[Route('/', name: 'admin_presentations_index', methods: ['GET'])]
    public function index(PresentationsRepository $presentationsRepository): Response
    {
        return $this->render('admin/presentations/index.html.twig', [
            'presentations' => $presentationsRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_presentations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Presentations $presentation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PresentationsType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_presentations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/presentations/edit.html.twig', [
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

<?php

namespace App\Controller\Streamer;

use App\Entity\Presentations;
use App\Form\PresentationsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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

            // Traitement des champs texte
            $presentation->setQuestion1(htmlspecialchars($request->request->get('question1')));
            $presentation->setQuestion2(htmlspecialchars($request->request->get('question2')));
            $presentation->setQuestion3(htmlspecialchars($request->request->get('question3')));
            
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

            $entityManager->flush();

            return $this->redirectToRoute('streamer_presentations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/presentations/edit.html.twig', [
            'form' => $form->createView(),
            'presentation' => $presentation,
        ]);
    }
}

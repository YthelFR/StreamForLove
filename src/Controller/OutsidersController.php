<?php

namespace App\Controller;

use App\Entity\Outsiders;
use App\Repository\OutsidersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/outsiders')]
class OutsidersController extends AbstractController
{
    #[Route('/', name: 'app_outsiders_index', methods: ['GET'])]
    public function index(OutsidersRepository $outsidersRepository): Response
    {
        return $this->render('outsiders/index.html.twig', [
            'outsiders' => $outsidersRepository->findAll(),
        ]);
    }

    #[Route('/{pseudo}', name: 'app_outsiders_show', methods: ['GET'])]
    public function show(OutsidersRepository $outsidersRepository, string $pseudo): Response
    {
        $outsider = $outsidersRepository->findOneBy(['pseudo' => $pseudo]);

        if (!$outsider) {
            throw $this->createNotFoundException('Outsider not found');
        }

        return $this->render('outsiders/show.html.twig', [
            'outsider' => $outsider,
        ]);
    }
}

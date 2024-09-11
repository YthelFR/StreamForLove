<?php

namespace App\Controller;

use App\Entity\Outsiders;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StreamerController extends AbstractController
{
    #[Route('/streamers', name: 'app_streamers')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $activeStreamers = $entityManager->getRepository(Users::class)->findByRole('ROLE_STREAMER_ACTIF');
        $inactiveStreamers = $entityManager->getRepository(Users::class)->findByRole('ROLE_STREAMER_ABSENT');

        // Récupérer tous les outsiders
        $outsiders = $entityManager->getRepository(Outsiders::class)->findAll();

        // Rendre le template Twig avec la liste des streamers actifs, absents et outsiders
        return $this->render('streamer/index.html.twig', [
            'activeStreamers' => $activeStreamers,
            'inactiveStreamers' => $inactiveStreamers,
            'outsiders' => $outsiders,
        ]);
    }

    #[Route('/streamers/{pseudo}', name: 'app_streamers_show', methods: ['GET'])]
    public function showStreamer(UsersRepository $usersRepository, string $pseudo): Response
    {
        $streamer = $usersRepository->findOneByPseudo($pseudo);

        if (!$streamer) {
            throw $this->createNotFoundException('Streamer not found');
        }

        return $this->render('streamer/show.html.twig', [
            'streamer' => $streamer,
        ]);
    }
}
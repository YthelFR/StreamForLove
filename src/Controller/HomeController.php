<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\TwitchApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private $twitchApiService;

    public function __construct(TwitchApiService $twitchApiService)
    {
        $this->twitchApiService = $twitchApiService;
    }

    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les utilisateurs de la base de données
        $users = $entityManager->getRepository(Users::class)->findAll();
        $liveUsers = [];

        // Vérifier si chaque utilisateur est en live et récupérer son avatar
        foreach ($users as $user) {
            if ($user->getPseudo() && $this->twitchApiService->isUserLive($user->getPseudo())) {
                $liveUsers[] = [
                    'pseudo' => $user->getPseudo(),
                    'avatar' => $user->getAvatar()
                ];
            }
        }

        // Rendre le template Twig avec les utilisateurs en live
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'liveUsers' => $liveUsers,
        ]);
    }
}

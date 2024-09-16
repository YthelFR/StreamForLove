<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\TwitchApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private $twitchApiService;

    public function __construct(TwitchApiService $twitchApiService)
    {
        $this->twitchApiService = $twitchApiService;
    }

    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        // $user = new Users();
        // $user->setEmail('therock666@therock666.fr')->setPseudo('therock666')->setPassword($hasher->hashPassword($user, '@therock66669'))->setValid(false)->setRoles(['ROLE_STREAMER_ACTIF'])->setCreatedAt(new \DateTimeImmutable());
        // $entityManager->persist($user);
        // $entityManager->flush();
        $users = $entityManager->getRepository(Users::class)->findAll();
        $liveUsers = [];

        // Vérifier si chaque utilisateur est en live et récupérer son avatar
        foreach ($users as $user) {
            if ($this->twitchApiService->isUserLive($user)) {
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

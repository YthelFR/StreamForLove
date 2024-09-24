<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\TwitchApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
        // $user->setEmail('masthom_@masthom_.fr')->setPseudo('Masthom_')->setPassword($hasher->hashPassword($user, '@masthom_69'))->setValid(false)->setRoles(['ROLE_STREAMER_ACTIF'])->setCreatedAt(new \DateTimeImmutable());
        // $entityManager->persist($user);
        // $entityManager->flush();
        $users = $entityManager->getRepository(Users::class)
            ->findByRole('ROLE_STREAMER_ACTIF');
        $liveUsers = [];

        foreach ($users as $user) {
            if ($this->twitchApiService->isUserLive($user)) {
                $channelInfo = $this->twitchApiService->getChannelInfo($user->getPseudo());
                $avatarUrl = $channelInfo['profile_image_url'] ?? '';

                $liveUsers[] = [
                    'pseudo' => $user->getPseudo(),
                    'avatar' => $avatarUrl
                ];
            }
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'liveUsers' => $liveUsers,
        ]);
    }
}

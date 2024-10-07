<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Form\EvenementsType;
use App\Repository\EvenementsRepository;
use App\Service\TwitchApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/evenements')]
final class EvenementsController extends AbstractController
{
    #[Route(name: 'app_evenements_index', methods: ['GET'])]
    public function index(EvenementsRepository $evenementsRepository): Response
    {
        return $this->render('evenements/index.html.twig', [
            'evenements' => $evenementsRepository->findAll(),
        ]);
    }

    #[Route('/{annee}', name: 'app_evenements_show', methods: ['GET'])]
    public function show(EvenementsRepository $evenementsRepository, int $annee, TwitchApiService $twitchApiService): Response
    {
        $evenements = $evenementsRepository->findByYear($annee);

        if (!$evenements) {
            throw $this->createNotFoundException('Aucun événement trouvé pour cette année.');
        }

        $evenement = $evenements[0];

        $participantsArray = $evenement->getParticipants()->toArray();
        usort($participantsArray, function ($a, $b) {
            return strcmp($a->getPseudo(), $b->getPseudo());
        });

        return $this->render('evenements/show.html.twig', [
            'evenement' => $evenement,
            'participants' => $participantsArray,
            'twitchApiService' => $twitchApiService,
        ]);
    }
}

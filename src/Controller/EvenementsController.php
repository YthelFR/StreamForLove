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

    #[Route('/{id}', name: 'app_evenements_show', methods: ['GET'])]
    public function show(Evenements $evenement, TwitchApiService $twitchApiService): Response
    {
        // Convertir les participants en tableau
        $participantsArray = $evenement->getParticipants()->toArray();

        // Trier les participants par ordre alphabétique selon le pseudo
        usort($participantsArray, function ($a, $b) {
            return strcmp($a->getPseudo(), $b->getPseudo());
        });

        // Passer le tableau trié à la vue
        return $this->render('evenements/show.html.twig', [
            'evenement' => $evenement,
            'participants' => $participantsArray, // Passer le tableau trié ici
            'twitchApiService' => $twitchApiService,
        ]);
    }
}

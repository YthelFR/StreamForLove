<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Form\EvenementsType;
use App\Repository\EvenementsRepository;
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
        // Récupère la liste des événements et les envoie à la vue
        return $this->render('evenements/index.html.twig', [
            'evenements' => $evenementsRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_evenements_show', methods: ['GET'])]
    public function show(Evenements $evenement): Response
    {
        // Affiche les détails d'un événement spécifique
        return $this->render('evenements/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }
}
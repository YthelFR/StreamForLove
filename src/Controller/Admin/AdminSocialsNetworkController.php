<?php

namespace App\Controller\Admin;

use App\Entity\SocialsNetwork;
use App\Form\SocialsNetworkType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSocialsNetworkController extends AbstractController
{
    #[Route('/admin/socials-network', name: 'admin_socials_network_index')]
    public function index(EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser(); 

        $socialsNetworks = $entityManager->getRepository(SocialsNetwork::class)
            ->findBy(['user' => $user]);

        return $this->render('admin/socials_network/index.html.twig', [
            'socialsNetworks' => $socialsNetworks,
        ]);
    }

    #[Route('/admin/socials-network/new', name: 'admin_socials_network_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        $socialsNetwork = new SocialsNetwork();
        $socialsNetwork->setUser($user);
        $form = $this->createForm(SocialsNetworkType::class, $socialsNetwork);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($socialsNetwork);
            $entityManager->flush();

            $this->addFlash('success', 'Réseau social ajouté avec succès.');  
            return $this->redirectToRoute('admin_socials_network_index');
        }

        return $this->render('admin/socials_network/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/socials-network/{id}/edit', name: 'admin_socials_network_edit')]
    public function edit(Request $request, SocialsNetwork $socialsNetwork, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SocialsNetworkType::class, $socialsNetwork);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Réseau social modifié avec succès.'); 
            return $this->redirectToRoute('admin_socials_network_index');
        }

        return $this->render('admin/socials_network/edit.html.twig', [
            'form' => $form->createView(),
            'socialsNetwork' => $socialsNetwork,
        ]);
    }

    #[Route('/admin/socials-network/{id}/delete', name: 'admin_socials_network_delete')]
    public function delete(SocialsNetwork $socialsNetwork, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($socialsNetwork);
        $entityManager->flush();

        $this->addFlash('success', 'Réseau social supprimé avec succès.');  
        return $this->redirectToRoute('admin_socials_network_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog_index')]
    public function index(Request $request, ArticlesRepository $articlesRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10; // Vous pouvez ajuster le nombre d'articles par page

        $pagination = $articlesRepository->findPaginatedArticles($page, $limit);

        return $this->render('blog/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/blog/{id}', name: 'app_blog_show')]
    public function show(Articles $article): Response
    {
        return $this->render('blog/show.html.twig', [
            'article' => $article,
        ]);
    }
}


<?php

namespace App\Controller;

use App\Model\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/movies', name: 'app_movies_list')]
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => Movie::LIST,
        ]);
    }

    #[Route('/movies/{slug}', name: 'app_movie_detail')]
    public function details(string $slug): Response
    {
        return $this->render('movie/details.html.twig', [
            'movie' => Movie::getBySlug($slug),
        ]);
    }
}

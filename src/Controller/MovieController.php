<?php

namespace App\Controller;

use App\Model\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function array_map;

class MovieController extends AbstractController
{
    #[Route('/movies', name: 'app_movies_list')]
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => array_map(Movie::fromMovieEntity(...), $movieRepository->findAll()),
        ]);
    }

    #[Route('/movies/{slug}', name: 'app_movie_detail')]
    public function details(MovieRepository $movieRepository, string $slug): Response
    {
        return $this->render('movie/details.html.twig', [
            'movie' => Movie::fromMovieEntity($movieRepository->getBySlug($slug)),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Movie as MovieEntity;
use App\Form\MovieType;
use App\Model\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function array_map;

class MovieController extends AbstractController
{
    #[Route('/movies', name: 'app_movies_list', methods: ['GET', 'POST'])]
    #[Route('/movies/{slug}/edit', name: 'app_movies_edit', methods: ['GET', 'POST'])]
    public function index(Request $request, MovieRepository $movieRepository, ?string $slug = null): Response
    {
        $movieEntity = new MovieEntity();
        if (null !== $slug) {
            $movieEntity = $movieRepository->getBySlug($slug);
        }

        $createOrEditMovieForm = $this->createForm(MovieType::class, $movieEntity);

        return $this->render('movie/index.html.twig', [
            'movies'                => array_map(Movie::fromMovieEntity(...), $movieRepository->listAll()),
            'createOrEditMovieForm' => $createOrEditMovieForm->createView(),
            'edit'                  => (null !== $slug),
        ]);
    }

    #[Route('/movies/{slug}', name: 'app_movie_detail', methods: ['GET'])]
    public function details(MovieRepository $movieRepository, string $slug): Response
    {
        return $this->render('movie/details.html.twig', [
            'movie' => Movie::fromMovieEntity($movieRepository->getBySlug($slug)),
        ]);
    }
}

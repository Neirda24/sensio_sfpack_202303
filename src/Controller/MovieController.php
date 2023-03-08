<?php

namespace App\Controller;

use App\Entity\Movie as MovieEntity;
use App\Form\MovieType;
use App\Model\Movie;
use App\Omdb\Client\OmdbApiConsumerInterface;
use App\Repository\MovieRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use function array_map;

class MovieController extends AbstractController
{
    public function __construct(
        private readonly MovieRepository          $movieRepository,
        private readonly OmdbApiConsumerInterface $omdbApiConsumer,
        private readonly SluggerInterface         $slugger,
    ) {
    }

    #[Route('/movies', name: 'app_movies_list', methods: ['GET', 'POST'])]
    #[Route('/movies/{slug}/edit', name: 'app_movies_edit', methods: ['GET', 'POST'])]
    public function index(Request $request, ?string $slug = null): Response
    {
        $movieEntity = new MovieEntity();
        if (null !== $slug) {
            $movieEntity = $this->movieRepository->getBySlug($slug);
        }

        $createOrEditMovieForm = $this->createForm(MovieType::class, $movieEntity);
        $createOrEditMovieForm->handleRequest($request);

        if ($createOrEditMovieForm->isSubmitted() && $createOrEditMovieForm->isValid()) {
            $this->movieRepository->save($movieEntity, true);

            return $this->redirectToRoute('app_movie_detail', ['slug' => $movieEntity->getSlug()]);
        }

        return $this->render('movie/index.html.twig', [
            'movies'                => array_map(Movie::fromMovieEntity(...), $this->movieRepository->listAll()),
            'createOrEditMovieForm' => $createOrEditMovieForm->createView(),
            'edit'                  => (null !== $slug),
        ]);
    }

    #[Route('/movies/{slug}', name: 'app_movie_detail', methods: ['GET'])]
    public function details(string $slug): Response
    {
        try {
            $movie = Movie::fromMovieEntity($this->movieRepository->getBySlug($slug));
        } catch (NoResultException $noResultException) {
            $movie = Movie::fromOmdbApiResult($this->omdbApiConsumer->getById($slug), $this->slugger);
        }

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }
}

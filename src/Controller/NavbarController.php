<?php

namespace App\Controller;

use App\Model\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use function array_map;

class NavbarController extends AbstractController
{
    public function __invoke(MovieRepository $movieRepository): Response
    {
        return $this->render('_partial/menu.html.twig', [
            'movies' => array_map(Movie::fromMovieEntity(...), $movieRepository->findAll()),
        ]);
    }
}

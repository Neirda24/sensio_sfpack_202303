<?php

namespace App\Controller;

use App\Model\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NavbarController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('_partial/menu.html.twig', [
            'movies' => Movie::LIST,
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SomethingController extends AbstractController
{
    #[Route('/quelque-chose-d-autre-ici', name: 'app_something')]
    public function index(): Response
    {
        return $this->render('something/index.html.twig');
    }
}

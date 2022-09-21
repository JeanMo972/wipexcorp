<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WhoWeAreController extends AbstractController
{
    #[Route('/who/we/are', name: 'app_who_we_are')]
    public function index(): Response
    {
        return $this->render('who_we_are/index.html.twig');
    }
}

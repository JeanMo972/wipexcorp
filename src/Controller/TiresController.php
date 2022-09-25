<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TiresController extends AbstractController
{
    #[Route('/nos-pneus', name: 'app_tires')]
    public function index(): Response
    {
        return $this->render('tires/index.html.twig', [
            'controller_name' => 'TiresController',
        ]);
    }
}

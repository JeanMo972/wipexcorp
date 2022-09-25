<?php

namespace App\Controller;

use App\Entity\Header;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index()
    {
        $headers = $this->entityManager->getRepository(Header::class)->findAll(false);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'headers'=> $headers
        ]);
    }
}

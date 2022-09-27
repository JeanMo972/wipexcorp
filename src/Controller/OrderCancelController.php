<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderCancelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;

    }

    #[Route('/commande/erreur/{stripeSessionId}', name: 'app_order_cancel')]
    public function index( $stripeSessionId): Response
    {
        $order = $this ->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        //si la commande n'existe pas OU que l'utilisateur ne correspond pas à celui actuellement connecté ALORS 
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('order_cancel/index.html.twig', [
           //afficher les quelques informations de la commande de l'utilisateur
            'order' => $order
        ]);
    }
}
<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;

    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_success')]
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this ->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        //dd($order);

        //si la commande n'existe pas OU que l'utilisateur ne correspond pas à celui actuellement connecté ALORS 
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }


          //si la commande est en statu NON PAYé 
        if (!$order->isIsPaid()){ 

            //vider la session "cart"
            $cart->remove();

            //modifier le statu isPaid de notre commande en mettant 1
            $order -> setIsPaid(1);

            //éxecute
            $this->entityManager->flush();
        }

        return $this->render('order_success/index.html.twig', [
           //afficher les quelques informations de la commande de l'utilisateur
            'order' => $order
        ]);
    }
}

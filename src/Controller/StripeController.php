<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{

    #[Route('/commmande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(EntityManagerInterface $entityManagerInterface, $reference, Cart $cart): Response
    {
        
        $products_for_stripe = [];

        //route 
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManagerInterface->getRepository(Order::class)->findOneByReference($reference);

        

        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }

        foreach ($cart->getFull() as $product) {
            //intégration de STRIPE
            //permet d'afficher le recapitulatif de la commande avant payment 
            $products_for_stripe[]= [ 
                    'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product['product']->getPrice(),
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'images' => ["http://127.0.0.1:8000/public/uploads"],
                    ],
                 ],
                'quantity' => ($product['quantity'])

            ];

        }
        //ajout du réglement transporteur dans la session de paymant stripe
        $products_for_stripe[] = [
            'price_data'=>[
                //monnaie
                'currency'=>'eur',
                //prix tranqsporteur
                'unit_amount'=>$order->getCarrierPrice()*100,
                //produit en base de donnée
                'product_data'=>[
                    'name'=>$order->getCarrierName(),
                    'images'=>["https://dirigeants-entreprise.com/content/uploads/Apps-Colissimo.jpg"],
                ],
            ],
            //quantité de transporteur
            'quantity'=>1,
            //dd($order->getOrderDetails()->getValues());
        ];
            //dd($products_for_stripe);
            // This is your test secret API key.
            Stripe::setApiKey('sk_test_51Ljoc2HXWDnJcARnUDTGShW2AetAwOCzbD7LBJ9H1r3419onwkzWHdxLY7psXofhxFswJhj6zFnN0hNP8NW0DZhz00BRdvHFsT');
            //dd($order);
            $checkout_session = Session::create([
                'customer_email' => $this->getUser()->getEmail(),
                'payment_method_types' => ['card'],
                'line_items' => [

                    $products_for_stripe
                ],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
                'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
              ]);
    
            $order->setStripeSessionId($checkout_session->id);
            
            $entityManagerInterface->flush();
            

            return $this->redirect($checkout_session->url);  
    }

}
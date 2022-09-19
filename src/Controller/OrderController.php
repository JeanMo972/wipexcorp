<?php

namespace App\Controller;

use DateTime;
use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart, Request $request ): Response

   
    {
          // Si l'utilisateur n'a pas d'adresses ALORS
        if (!$this->getUser()->getAddresses()->getValues()) {
            // On le redirige vers la page d'ajout d'adresse
        return $this->redirectToRoute('app_account_address_add');
    }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig',[
            'form' =>$form->createView(),
            'cart' =>$cart->getFull()

   
        ]);
    }

        #[Route('/commande/recapitulatif', name: 'order_recap',
        methods:'POST')]
        public function add(Cart $cart, Request $request): Response

   
    {
          // Si l'utilisateur n'a pas d'adresses ALORS
        if (!$this->getUser()) {
            // On le redirige vers la page d'ajout d'adresse
        return $this->redirectToRoute('app_account_address_add');
    }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        // Ecoute la requête
        $form->handleRequest($request);

        // SI le formulaire est soumis ET le formulaire est valide ALORS
        if ($form->isSubmitted() && $form->isValid()) {

        //{dd($form->getData()); 

            $order = new Order();
            $date = new DateTime;
            $carriers = $form->get('carriers')->getData();//soumet les data carriers

        //dd($carriers);

            //enregistrer  
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .= '<br>'.$delivery->getPhone();

            if ($delivery->getCompagny()) {
                $delivery_content .= '<br>'.$delivery->getCompany();
            }

            $delivery_content .= '<br>'.$delivery->getAddress();
            $delivery_content .= '<br>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '<br>'.$delivery->getContry();

        //dd($delivery_content);

        //dd($delivery);
            //enregistrer ma commande Order()
            $order->setUser($this->getUser());//enregistre la commande de l'utilisateur en base de donnée
            $order->setCreatedAt($date);//Date de l'enregistrement la commande de l'utilisateur
            $order->setCarrierName($carriers->getName());//enregistre le nom du transporteur en base de donnée
            $order->setCarrierPrice($carriers->getPrice());//enregistre le prix de la livaison en base de donnée;
            $order->setDelivery($delivery_content);//enregistre l'adresse de livraison en base de donnée
            $order->setIsPaid(0);//enregistre le paimant en basse de donnée

            //Fige la DATA(order)
            $this->entityManager->persist($order);
            
            //enregistrer mes produits OrderDetails()pour chaque produit que j'ai dans mon panier
            foreach ($cart->getFull() as $product) {
            //Enregistrer ma commande(**Order()**)
            $orderDetails = new OrderDetails();

            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);

            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($product['product']->getName());
            $orderDetails->setQuantity($product['quantity']);
            $orderDetails->setPrice($product['product']->getPrice());
            $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);


            //Fige la DATA(orderDetails)
            $this->entityManager->persist($orderDetails);
            
            //dd($product);
            }

            //enregistre la commande en base de commande dans orderDetails
            $this->entityManager->flush();

            //dd($order);         

            return $this->render('order/add.html.twig',[
                'cart' =>$cart->getFull(),//affiche le panier complet
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference' => $order->getReference(),
               
                ]);

            }
            
            return $this->redirectToRoute('app_cart');
            
        }
     
    }
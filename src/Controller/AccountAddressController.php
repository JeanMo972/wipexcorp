<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AccountAddressController extends AbstractController
{
    // securisation de notre acces a doctrine  
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/compte/addresses', name: 'app_account_address')]
    public function index(): Response
    {
        // dd($this->getUser());
        return $this->render('account/address.html.twig',);
    }







    #[Route('/compte/ajouter-une-adresse', name: 'app_account_address_add')]
    public function add(Cart $cart, Request $request): Response
    {
        $address = new Address;
        $form = $this->createForm(AddressType::class, $address);
        
        $form->handleRequest($request);

        // pour verfifier
        // si le formulaire est envoyer et est valide alors
        if ($form->isSubmitted() && $form->isvalid()){
           //information afficher dans le terminal
           //dis a l'adress de prendre l'uttilisateur actuel
            $address->setUser($this->getUser());
           
            // dd($address);


            //fige la data
            $this->entityManager->persist($address);
            //Execute
            $this->entityManager->flush();

            // S'il y a un produit dans le panier
            if ($cart->get()) {
                // JE redirige vers commande
                return $this->redirectToRoute('app_order');
            } else {
                return $this->redirectToRoute('app_account_address');
            }
        }

        return $this->render('account/address_form.html.twig',[
            'form' => $form->createView(),
        ]);

    }
    #[Route('/compte/modifier-une-address/{id}', name: 'app_account_address_edit')]
    public function edit(Request $request,$id): Response
    {
        // je recupere l'adresse dans la basse de donne grace a entity manager
        // et c'est grace a doctrine que je peut recuperer l'adreese grace a l'id
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        // si 
        if (!$address || $address->getUser() != $this ->getUser()){
            return $this->redirectToRoute('app_account_address');
        }

        $form = $this->createForm(AddressType::class, $address);
        // ecoute la requette
        $form->handleRequest($request);

        // pour verfifier
        // si le formulaire est envoyer et est valide alors
        if ($form->isSubmitted() && $form->isvalid()){
           //information afficher dans le terminal
           //dis a l'adress de prendre l'uttilisateur actuel
            $address->setUser($this->getUser());
           
            // dd($address);


           
            //Execute
            $this->entityManager->flush();

            

            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/address_form.html.twig',[
            'form' => $form->createView(),
        ]);

    }
    // je passe l'id de mon uttilisateru de mon address a la vue
    #[Route('/compte/suprimer-une-address/{id}', name: 'app_account_address_remove')]
    public function remove(Request $request,$id): Response
    {
        // je recupere l'adresse dans la basse de donne grace a entity manager
        // et c'est grace a doctrine que je peut requper l'adreese grace a l'id
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        // si  il ya une adresse et que l'uttilisateur correspont a l'uttilisateur connecter
        if (!$address || $address->getUser() == $this ->getUser()){
            // suprime l'object addresse dans la basse de donne
            $this->entityManager->remove($address);
             //Execute
             $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_account_address');
    }
}
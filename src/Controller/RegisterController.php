<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegisterController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)//injection de dépance
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response //injection des dependance (Managerregister pour envoyer des données en db)
    {
        $user = new User(); //nouvel utilisateur
        $form = $this->createForm(RegisterType::class, $user); // creation d'un formulaire

        $form->handleRequest($request);// ecoute la requete

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();// on ajoute une instance

            $password = $passwordHasher->hashPassword($user, $user->getPassword());     //password crypter

            //dd($password); var_dump débboger (password)

            $user->setPassword($password);

            $this->entityManager->persist($user);

            $this->entityManager->flush();
           
        }
          
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'account')]
    public function index(): Response
    {
        $user = new User();//nouvelle utilisateur

        $form = $this->createForm(ChangePasswordType::class, $user);//nouveau fourmulaire
        

        return $this->render('account/index.html.twig');//renvoi la page
    }
}

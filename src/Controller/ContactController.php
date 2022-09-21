<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/Contacter-nous', name: 'app_contact')] //route vers contacter-nous
    public function index(Request $request)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', 'Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais.');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()  
        ]);
    }
}

<?php

namespace App\Controller;


use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; //injection de dépendance//
    }
    #[Route('/nos-produits', name: 'app_products')] //route//
    public function index(Request $request): Response
    {
       
            $search = new Search();

            $form = $this->createform(SearchType::class,$search);

            $form->handleRequest($request);//Ecoute la requete//


            

            if($form->isSubmitted() && $form->isValid()) {
                $products = $this->entityManager->getRepository(Product::class)->findwithSearch($search);
              
            }else{$products= $this->entityManager->getRepository(Product::class)->findAll();
                
                }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
           
        ]);
    }

    #[Route('/produit/{slug}', name: 'app_product')] //route//
    public function show($slug):Response
    {
        // dd($slug);
        // On recherche en base de donnée un produit associer à son slug.
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);

         // Partie sécurité
        if (!$product){
            return $this->redirectToRoute('app_products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}

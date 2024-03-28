<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/back/produits', name: 'app_back_produit')]
    public function index(ProduitRepository $produitRepository): Response
    {

        return $this->render('back/produit/allProduit.html.twig',[
            "produits"=>$produitRepository->findAll(),
        ]);
    }

    #[Route('/back/produits/add', name: 'app_back_produit_add')]
    public function addProduit(ProduitRepository $produitRepository): Response
    {

        return $this->render('back/produit/allProduit.html.twig',[
            "produits"=>$produitRepository->findAll(),
        ]);
    }

    #[Route('/back/produits/{id}/edit', name: 'update_product')]
    public function edit(Produit $produit, Request $request)
    {
        // Handle the form submission for updating the product
        // You can use Symfony forms to create and handle the update form
        
        // Example:
        // $form = $this->createForm(ProduitType::class, $produit);
        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $this->getDoctrine()->getManager()->flush();
        //     return $this->redirectToRoute('app_back');
        // }

        // Render the update form template
        // Example:
        // return $this->render('back/produit/editProduit.html.twig', [
        //     'form' => $form->createView(),
        // ]);

        // Replace the above with your actual implementation
    }

    #[Route('/back/produits/{id}/delete', name: 'delete_product')]
    public function delete(Produit $produit,ManagerRegistry $mr): Response
    {
        $entityManager = $mr->getManager();
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('app_back');
    }
    
}

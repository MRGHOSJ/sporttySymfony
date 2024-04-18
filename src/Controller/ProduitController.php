<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
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
    public function addProduit  (Request $request, ManagerRegistry $manager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_back_produit');
        }

        return $this->render('back/produit/formProduit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/produits/{id}/edit', name: 'update_product')]
    public function edit(Request $request, ManagerRegistry $manager,ProduitRepository $produitRepository,int $id)
    {
        $produit = $produitRepository->find($id);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_back_produit');
        }

        return $this->render('back/produit/formProduit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/produits/{id}/delete', name: 'delete_product')]
    public function delete(Produit $produit,ManagerRegistry $mr): Response
    {
        $entityManager = $mr->getManager();
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('app_back');
    }



    
    #[Route('/produit', name: 'app_front_produit')]
    public function produitFront(ProduitRepository $produitRepository): Response
    {
        return $this->render('front/produit/pricing.html.twig',[
            'produits'=>$produitRepository->findAll(),
        ]);
    }
    
}

<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierProduit;
use App\Entity\Produit;
<<<<<<< Updated upstream
=======
use App\Form\ProduitType;
use App\Repository\PanierProduitRepository;
use App\Repository\PanierRepository;
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/back/produits', name: 'app_back_produit')]
    public function index(Request $request, ProduitRepository $produitRepository): Response
<<<<<<< Updated upstream
    {
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $produitRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        return $this->render('back/produit/allProduit.html.twig',[
            "produits"=>$items,
        ]);
    }

    #[Route('/back/produits/add', name: 'app_back_produit_add')]
    public function addProduit(ProduitRepository $produitRepository): Response
=======
>>>>>>> Stashed changes
    {
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $produitRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        return $this->render('back/produit/allProduit.html.twig',[
            "produits"=>$items,
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
    public function delete(int $id,ManagerRegistry $mr,ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->find($id);
        $entityManager = $mr->getManager();
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('app_back_produit');
    }
<<<<<<< Updated upstream
=======



    
    #[Route('/produit', name: 'app_front_produit')]
    public function produitFront(Request $request,ProduitRepository $produitRepository, PanierRepository $panierRepository,SessionInterface $session, PanierProduitRepository $panierProduitRepository): Response
    {
        $numberPanier = 0;
        $panierId = $session->get('panier_id');
        if ($panierId) {
            $panier = $panierRepository->find($panierId);

            if($panier)
            {
                $panierProduits = $panierProduitRepository->findBy(['panierId' => $panierId]);
                foreach ($panierProduits as $panierProduit) {
                    $quantite = $panierProduit->getQuantite();
                    $numberPanier+= $quantite;
                }
            }
        }
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $produitRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        return $this->render('front/produit/pricing.html.twig',[
            'produits'=>$items,
            'numberPanier' => $numberPanier,
        ]);
    }

    #[Route('/produit/panier/add/{idProduit}', name: 'app_front_produitPanier')]
    public function produitPanierFront($idProduit,ProduitRepository $produitRepository, ManagerRegistry $mr, PanierProduitRepository $panierProduitRepository, SessionInterface $session, PanierRepository $panierRepository): Response
    {
        $panierId = $session->get('panier_id');

        //search panier for user
        $panier = $panierRepository->find($panierId);

        if(!$panier)
        {
            $panier = new Panier();
            $panier->setId($panierId);
            $panier->setIdUser(0);
            
            $em = $mr->getManager();
            $em->persist($panier);
            $em->flush();
        }

        if (!$panierId) {
            $biggestPanierId = $panierProduitRepository->findBiggestPanierId();
            
            $panierId = $biggestPanierId ? $biggestPanierId + 1 : 1;

            $session->set('panier_id', $panierId);

        }

        $produit = $produitRepository->find($idProduit);

        $existingPanierProduit = $panierProduitRepository->findOneBy([
            'panierId' => $panierId,
            'produitId' => $idProduit,
        ]);
        
        if ($produit->getQte() > 0)
        {
            if (!$existingPanierProduit) {
                $panierProduit = new PanierProduit();
                $panierProduit->setPanierId($panierId);
                $panierProduit->setProduitId($idProduit);
                $panierProduit->setQuantite(1);
                
                $em = $mr->getManager();
                $em->persist($panierProduit);
                $em->flush();
            } else {
                $quantite = $existingPanierProduit->getQuantite();
                $existingPanierProduit->setQuantite($quantite+1);

                
                $em = $mr->getManager();
                $em->persist($existingPanierProduit);
                $em->flush();
            }
            $produit->setQte($produit->getQte() - 1);
            
            $em = $mr->getManager();
            $em->persist($produit);
            $em->flush();
        }

        return $this->redirectToRoute('app_front_produit');
    }
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    
}

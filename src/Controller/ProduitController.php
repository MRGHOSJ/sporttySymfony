<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierProduit;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\PanierProduitRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Service\MailService;
use App\Service\OpenAIChatService;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/back/produits', name: 'app_back_produit')]
    public function index(Request $request, ProduitRepository $produitRepository,PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $produitRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        
        $pagination = $paginator->paginate(
            $items,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('back/produit/allProduit.html.twig',[
            "produits"=>$items,
            "pagination"=>$pagination,
        ]);
    }

    #[Route('/back/produits/add', name: 'app_back_produit_add')]
    public function addProduit  (Request $request, ManagerRegistry $manager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'image
            $imageFile = $form->get('image')->getData();

            // Vérification et traitement de l'image
            if ($imageFile) {
                $nomFichier = md5(uniqid()) . '.' . $imageFile->guessExtension();

                // Déplacement du fichier vers le dossier approprié
                $imageFile->move(
                    $this->getParameter('dossier_images'), // Paramètre défini dans config/services.yaml
                    $nomFichier
                );

                // Stockage du nom du fichier dans l'entité     
                $produit->setImage($nomFichier);
            }

            $em=$manager->getManager();
            $em->persist($produit);
            $em->flush();
            
            $mailService = new MailService(); 
            $recipient = 'bouzouitayassine@gmail.com';
            $subject = 'Product Added';
            $htmlContent = $mailService->readHTMLFile('mail/notification/SUCCESS.html');
            $htmlContent = str_replace("{Title}", "Product Added", $htmlContent);
            $htmlContent = str_replace("{Description}", 
            "Name: ".$produit->getNom().   "<br>". 
            "Price: ".$produit->getPrix().   "<br>". 
            "Quantite: ".$produit->getQte().   "<br>". 
            "Description: ".$produit->getDescription().   "<br>"
            
            , $htmlContent);
            
            $mailService->sendMail($recipient, $subject, $htmlContent);
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
            $imageFile = $form->get('image')->getData();

            // Vérification et traitement de l'image
            if ($imageFile) {
                $nomFichier = md5(uniqid()) . '.' . $imageFile->guessExtension();

                // Déplacement du fichier vers le dossier approprié
                $imageFile->move(
                    $this->getParameter('dossier_images'), // Paramètre défini dans config/services.yaml
                    $nomFichier
                );

                // Stockage du nom du fichier dans l'entité     
                $produit->setImage($nomFichier);
            }

            $em=$manager->getManager();
            $em->persist($produit);
            $em->flush();
            
            $mailService = new MailService(); 
            $recipient = 'bouzouitayassine@gmail.com';
            $subject = 'Product Updated';
            $htmlContent = $mailService->readHTMLFile('mail/notification/SUCCESS.html');
            $htmlContent = str_replace("{Title}", "Product Updated", $htmlContent);
            $htmlContent = str_replace("{Description}", 
            "Name: ".$produit->getNom().   "<br>". 
            "Price: ".$produit->getPrix().   "<br>". 
            "Quantite: ".$produit->getQte().   "<br>". 
            "Description: ".$produit->getDescription().   "<br>"
            
            , $htmlContent);
            
            $mailService->sendMail($recipient, $subject, $htmlContent);
            return $this->redirectToRoute('app_back_produit');
        }

        return $this->render('back/produit/formProduit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/produits/{id}/delete', name: 'delete_product')]
    public function delete(int $id,ManagerRegistry $mr,ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->find($id);
        $entityManager = $mr->getManager();
        $entityManager->remove($produit);
        $entityManager->flush();

        $mailService = new MailService(); 
        $recipient = 'bouzouitayassine@gmail.com';
        $subject = 'Product Deleted';
        $htmlContent = $mailService->readHTMLFile('mail/notification/ALERT.html');
        $htmlContent = str_replace("{Title}", "Product Deleted", $htmlContent);
        $htmlContent = str_replace("{Description}", 
        "Name: ".$produit->getNom().   "<br>". 
        "Price: ".$produit->getPrix().   "<br>". 
        "Quantite: ".$produit->getQte().   "<br>". 
        "Description: ".$produit->getDescription().   "<br>"
        
        , $htmlContent);
        
        $mailService->sendMail($recipient, $subject, $htmlContent);
        return $this->redirectToRoute('app_back_produit');
    }


    #[Route('/chat', name: 'app_front_chat')]
    public function FrontChatBot(
        Request $request,
        OpenAIChatService $chatService
    ): Response {
        $userMessage = $request->get('message'); 
        if ($userMessage) {
            $chatResponse = $chatService->sendMessage($userMessage);
        } else {
            $chatResponse = '';
        }

        // Render the chat page with the conversation history
        return $this->render('front/produit/chatBot.html.twig', [
            'userMessage' => $userMessage,
            'chatResponse' => $chatResponse,
        ]);
    }
    
    #[Route('/produit', name: 'app_front_produit')]
    public function produitFront(Request $request,ProduitRepository $produitRepository, PanierRepository $panierRepository,SessionInterface $session, PanierProduitRepository $panierProduitRepository,PaginatorInterface $paginator): Response
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

        
        $pagination = $paginator->paginate(
            $items,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('front/produit/pricing.html.twig',[
            'produits'=>$items,
            'numberPanier' => $numberPanier,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/produit/panier/add/{idProduit}', name: 'app_front_produitPanier')]
    public function produitPanierFront($idProduit,ProduitRepository $produitRepository, ManagerRegistry $mr, PanierProduitRepository $panierProduitRepository, SessionInterface $session, PanierRepository $panierRepository): Response
    {
        $panierId = $session->get('panier_id');


        if (!$panierId) {
            $biggestPanierId = $panierProduitRepository->findBiggestPanierId();
            
            $panierId = $biggestPanierId ? $biggestPanierId + 1 : 1;

            $session->set('panier_id', $panierId);

        }

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
            flash()->addSuccess('Added '. $produit->getNom() .' To Basket Successfully');
            $produit->setQte($produit->getQte() - 1);
            
            $em = $mr->getManager();
            $em->persist($produit);
            $em->flush();
        }

        return $this->redirectToRoute('app_front_produit');
    }
    
}

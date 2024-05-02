<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierPanier;
use App\Entity\Produit;
use App\Form\PanierType;
use App\Repository\PanierPanierRepository;
use App\Repository\PanierProduitRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Service\TwilioService;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/back/Paniers', name: 'app_back_Panier')]
    public function index(PanierRepository $PanierRepository): Response
    {
        return $this->render('back/Panier/allPanier.html.twig',[
            "Paniers"=>$PanierRepository->findAll(),
        ]);
    }


    #[Route('/back/Paniers/{id}/delete', name: 'delete_panier')]
    public function delete(Panier $Panier,ManagerRegistry $mr): Response
    {
        $entityManager = $mr->getManager();
        $entityManager->remove($Panier);
        $entityManager->flush();

        return $this->redirectToRoute('app_back');
    }



    
    #[Route('/panier', name: 'app_front_Panier')]
    public function pranierFront(ProduitRepository $produitRepository, PanierRepository $panierRepository,SessionInterface $session, PanierProduitRepository $panierProduitRepository): Response
    {
        $prixTotal = 0;
        $numberPanier = 0;
        $panierId = $session->get('panier_id');
        $panierProduits = [];
        if ($panierId) {
            $panier = $panierRepository->find($panierId);

            if($panier)
            {
                $panierProduits = $panierProduitRepository->findBy(['panierId' => $panierId]);
                foreach ($panierProduits as $panierProduit) {
                    $quantite = $panierProduit->getQuantite();
                    $numberPanier+= $quantite;
                    $produit = $produitRepository->find($panierProduit->getProduitId());
                    $prixTotal += $quantite * $produit->getPrix();
                }
            }
        }
        return $this->render('front/panier/pricing.html.twig',[
            'produits'=>$produitRepository->findAll(),
            "panierProduits" => $panierProduits,
            "prixTotal" => $prixTotal,
        ]);
    }

    #[Route('/panier/produit/removeOne/{id}', name: 'app_front_Panier_produit_removeone')]
    public function pranierFrontRemoveOne(int $id,ProduitRepository $produitRepository, PanierRepository $panierRepository,SessionInterface $session, PanierProduitRepository $panierProduitRepository,ManagerRegistry $manager)
    {
        $panierId = $session->get('panier_id');

        if ($panierId) {
            $panier = $panierRepository->find($panierId);

            if($panier)
            {
                $panierProduits = $panierProduitRepository->findBy(['panierId' => $panierId]);
                foreach ($panierProduits as $panierProduit) {
                     if($panierProduit->getProduitId() == $id)
                     {
                        $quantite = $panierProduit->getQuantite();
                        $em=$manager->getManager();

                        if($quantite > 2){
                            $panierProduit->setQuantite($panierProduit->getQuantite()-1);
                            $em->persist($panierProduit);
                            $em->flush();
                            return $this->redirectToRoute('app_front_Panier');
                        }else{
                            $em->remove($panierProduit);
                            $em->flush();
                            return $this->redirectToRoute('app_front_Panier');
                        }  
                        
                        flash()->addError('Removed Product From Basket');
                     }
                }
            }
        }
    }

    #[Route('/panier/produit/delete/{id}', name: 'app_front_Panier_produit_removeProduit')]
    public function pranierFrontRemoveProduit(int $id,ProduitRepository $produitRepository, PanierRepository $panierRepository,SessionInterface $session, PanierProduitRepository $panierProduitRepository,ManagerRegistry $manager)
    {
        $panierId = $session->get('panier_id');

        if ($panierId) {
            $panier = $panierRepository->find($panierId);

            if($panier)
            {
                $panierProduits = $panierProduitRepository->findBy(['panierId' => $panierId]);
                foreach ($panierProduits as $panierProduit) {
                     if($panierProduit->getProduitId() == $id)
                     {
                        $em=$manager->getManager();

                        $em->remove($panierProduit);
                        $em->flush();
                        flash()->addError('Removed Product From Basket');
                        return $this->redirectToRoute('app_front_Panier');
                          
                     }
                }
            }
        }
    }

    #[Route('/pay/{price}', name: 'app_front_Panier_produit_pay')]
    public function pay($price,ProduitRepository $produitRepository, PanierRepository $panierRepository,SessionInterface $session, PanierProduitRepository $panierProduitRepository,ManagerRegistry $manager)
    {
        Stripe::setApiKey('sk_test_51PBoOb2LYYbUtOuQQfR9VV1RvG7ell2uW1ZCfttVtvQLeGyM2OpDxSFgPzqnIq0pWWlTiQOGiMsXCqPYFz7j13PF00wnqnFtG2');

        $panierId = $session->get('panier_id');

        if ($panierId) {
            $panier = $panierRepository->find($panierId);

            if($panier)
            {
                $panierProduits = $panierProduitRepository->findBy(['panierId' => $panierId]);
                foreach ($panierProduits as $panierProduit) { 
                    $em=$manager->getManager();

                    $em->remove($panierProduit);
                    $em->flush();
                    
                }
                $totalPriceTND = $price;

                $conversionRate = 0.313;
                $totalPriceEUR = $totalPriceTND * $conversionRate;

                PaymentIntent::create([
                    'amount' => $totalPriceEUR  * 100, 
                    'currency' => 'eur',
                ]);
                
                $recipient = '+21658067859';
                $message = 'Payment Proccessed Successfully Total Price '.$totalPriceEUR.'Euro ( '.$totalPriceTND.'DT)';

                $twilioService = new TwilioService('AC8178a69781ae3148d452506cf6469643', '05d71db859eb6709c6c1b7d4b7dcc8f3', '+13345132497');
                $isSent = $twilioService->sendSMS($recipient, $message);

                if ($isSent) {
                    return $this->redirectToRoute('app_front_Panier');
                } else {
                    return new Response('Failed to send SMS.');
                }

            }
        }
    }

    #[Route('/panier/print', name:"app_panier_print")]
    public function convertToPdf(ProduitRepository $produitRepository, PanierRepository $panierRepository,SessionInterface $session, PanierProduitRepository $panierProduitRepository): Response
    {
        $panierId = $session->get('panier_id');

        if (!$panierId) {
            throw $this->createNotFoundException('PanierId not found');
        }

        $panier = $panierRepository->find($panierId);
        $prixTotal = 0;
        
        $productTableAffichage = "";

        if($panier)
        {
            $panierProduits = $panierProduitRepository->findBy(['panierId' => $panierId]);
            foreach ($panierProduits as $panierProduit) {
                $quantite = $panierProduit->getQuantite();
                $produit = $produitRepository->find($panierProduit->getProduitId());
                $prixTotal += $quantite * $produit->getPrix();
                $productTableAffichage .= "<tr>
                                                <td>". $produit->getNom() ."</td>
                                                <td>". $quantite ."x ". $produit->getPrix() ." DT</td>
                                            </tr>";
            }
        }
        $htmlFilePath = $this->getParameter('kernel.project_dir') . '/public/pdf/payment_form.html';

        $html = file_get_contents($htmlFilePath);

            
        $html = str_replace('{totalPrice}', $prixTotal, $html);
        $html = str_replace('{items}', $productTableAffichage, $html);

        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="recu.pdf"'
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Cabine;
use App\Form\CabineType;
use App\Repository\CabineRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile; 
use Doctrine\ORM\EntityManagerInterface;


class CabineController extends AbstractController
{
    #[Route('/back/cabines', name: 'app_back_cabine')]
    public function index(Request $request,CabineRepository $CabineRepository): Response
    {
        
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $CabineRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        return $this->render('back/cabines/allcabine.html.twig',[
            "cabines"=>$items,
        ]);
    }

    #[Route('/back/cabines/add', name: 'app_back_cabine_add')]
    public function addcabine  (Request $request, ManagerRegistry $manager): Response
    {
        $cabine = new Cabine();
        $form = $this->createForm(CabineType::class, $cabine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($cabine);
            $em->flush();
            return $this->redirectToRoute('app_back_cabine');
        }

        return $this->render('back/cabines/formcabine.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/cabines/{id}/edit', name: 'update_cabine')]
    public function edit(Request $request, ManagerRegistry $manager,CabineRepository $cabineRepository,int $id)
    {
        $cabine = $cabineRepository->find($id);
        $form = $this->createForm(CabineType::class, $cabine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
            {
                $imageFile = $form->get('image')->getData();
    
                // Vérification et traitement de l'image
                if ($imageFile) {
                    $nomFichier = md5(uniqid()) . '.' . $imageFile->guessExtension();
    
                    // Déplacement du fichier vers le dossier approprié
                    $imageFile->move(
                        $this->getParameter('dossier_images'), // Paramètre défini dans config/services.yaml
                        $nomFichier
                    );
    
                    // Stockage du nom du fichier dans l'entité Materiel
                    $cabine->setImage($nomFichier);
            }
            $em=$manager->getManager();
            $em->persist($cabine);
            $em->flush();
            return $this->redirectToRoute('app_back_cabine');
        }

        return $this->render('back/cabines/formcabine.html.twig', [
            'form' => $form->createView()
        ]);
    
    
}

    #[Route('/back/cabines/{id}/delete', name: 'delete_cabine')]
    public function delete(CabineRepository $cabineRepository,ManagerRegistry $mr): Response
    {
        $cabine = $cabineRepository->find($id);
        $entityManager = $mr->getManager();
        $entityManager->remove($cabine);
        $entityManager->flush();

        return $this->redirectToRoute('app_back_cabine');
    }



    
    #[Route('/gyms/{id}/cabine', name: 'app_front_cabine')]
    public function cabineFront(CabineRepository $cabineRepository,int $id): Response
    {
        $cabines = $cabineRepository->findBy(['idSalle' => $id]);
        return $this->render('front/cabine/pricing.html.twig',[
            'cabines'=>$cabines,
        ]);
    }

                            
    #[Route("/generate-pdf", name: "app_generate_pdfcabine")]
    public function generatePdf(Request $request): Response
    {
        // Récupérer la liste des matériels depuis la base de données
        $cabine = $this->getDoctrine()->getRepository(Cabine::class)->findAll();

        // Créer une instance de Dompdf
        $dompdf = new Dompdf();

        // Construction du contenu HTML à partir du template twig
        $html = $this->renderView('back/cabines/pdf.html.twig', [
            'cabine' => $cabine,
        ]);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // (Optionnel) Paramètres de la mise en page
        $dompdf->setPaper('A4', 'portrait');

        // Rendu du PDF
        $dompdf->render();

        // Générer le nom du fichier PDF
        $pdfFileName = 'liste_Cabines.pdf';

        // Créer une réponse avec le contenu du PDF
        $response = new Response($dompdf->output());

        // Ajouter les en-têtes pour indiquer au navigateur de télécharger le fichier
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $pdfFileName . '"');

        // Retourner la réponse
        return $response;
    }
    
}

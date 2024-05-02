<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Repository\MaterielRepository;
use App\Service\MailService;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaterielController extends AbstractController
{
    #[Route('/back/materiels', name: 'app_back_Materiel')]
    public function index(Request $request,MaterielRepository $MaterielRepository,PaginatorInterface $paginator): Response
    {
        
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $MaterielRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);
        
        $pagination = $paginator->paginate(
            $items,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('back/Materiel/allMateriel.html.twig',[
            "Materiels"=>$items,
            "pagination"=>$pagination,
        ]);
    }

    #[Route('/back/materiels/add', name: 'app_back_Materiel_add')]
    public function addMateriel  (Request $request, ManagerRegistry $manager): Response
    {
        $Materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $Materiel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
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

                // Stockage du nom du fichier dans l'entité Materiel
                $Materiel->setImage($nomFichier);
            }
            $em->persist($Materiel);
            $em->flush();
            $mailService = new MailService(); 

            $recipient = 'bouzouitayassine@gmail.com';
            $subject = 'Materiel Added';
            $htmlContent = $mailService->readHTMLFile('mail/notification/INFO.html');
            $htmlContent = str_replace("{Title}", "Materiel Added", $htmlContent);
            $htmlContent = str_replace("{Description}", 
            "Nom: ".$Materiel->getNom().   "<br>" .
            "Categorie: ".$Materiel->getCategorie().   "<br>"             
            , $htmlContent);
            
            $mailService->sendMail($recipient, $subject, $htmlContent);

            return $this->redirectToRoute('app_back_Materiel');
        }

        return $this->render('back/Materiel/formMateriel.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/materiels/{id}/edit', name: 'update_Materiel')]
    public function edit(Request $request, ManagerRegistry $manager,MaterielRepository $MaterielRepository,int $id)
    {
        $Materiel = $MaterielRepository->find($id);
        $form = $this->createForm(MaterielType::class, $Materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($Materiel);
            $em->flush();
            $mailService = new MailService(); 

            $recipient = 'bouzouitayassine@gmail.com';
            $subject = 'Materiel Updated';
            $htmlContent = $mailService->readHTMLFile('mail/notification/INFO.html');
            $htmlContent = str_replace("{Title}", "Materiel Updated", $htmlContent);
            $htmlContent = str_replace("{Description}", 
            "Nom: ".$Materiel->getNom().   "<br>" .
            "Categorie: ".$Materiel->getCategorie().   "<br>"             
            , $htmlContent);
            
            $mailService->sendMail($recipient, $subject, $htmlContent);

            return $this->redirectToRoute('app_back_Materiel');
        }

        return $this->render('back/Materiel/formMateriel.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/materiels/{id}/delete', name: 'delete_Materiel')]
    public function delete(ManagerRegistry $mr,MaterielRepository $MaterielRepository,int $id): Response
    {
        $Materiel = $MaterielRepository->find($id);
        $entityManager = $mr->getManager();
        $entityManager->remove($Materiel);
        $entityManager->flush();

        $mailService = new MailService(); 

        $recipient = 'bouzouitayassine@gmail.com';
        $subject = 'Materiel Deleted';
        $htmlContent = $mailService->readHTMLFile('mail/notification/ALERT.html');
        $htmlContent = str_replace("{Title}", "Materiel Deleted", $htmlContent);
        $htmlContent = str_replace("{Description}", 
        "Nom: ".$Materiel->getNom().   "<br>" .
        "Categorie: ".$Materiel->getCategorie().   "<br>"             
        , $htmlContent);
        
        $mailService->sendMail($recipient, $subject, $htmlContent);

        return $this->redirectToRoute('app_back');
    }



    
    #[Route('/materiels', name: 'app_front_Materiel')]
    public function MaterielFront(Request $request, MaterielRepository $MaterielRepository): Response
    {
        
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $MaterielRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        return $this->render('front/Materiel/pricing.html.twig',[
            'Materiels'=>$items,
        ]);
    }

    #[Route('/admin/materiels/pdf', name: 'app_back_materiel_pdf')]
    public function generatePdf(MaterielRepository $MaterielRepository): Response
    {
        $materiels = $MaterielRepository->findAll();

        if (!$materiels) {
            throw $this->createNotFoundException('Materiel not found');
        }

        // Construct HTML content
        $htmlFilePath = $this->getParameter('kernel.project_dir') . '/public/pdf/materiel.html';
        $html = file_get_contents($htmlFilePath);

        // Check if HTML content was loaded successfully
        if ($html === false) {
            throw new \Exception('Failed to load HTML content');
        }

        $htmlFilePath = $this->getParameter('kernel.project_dir') . '/public/pdf/materiel.html';
        $html = file_get_contents($htmlFilePath);

        // Check if HTML content was loaded successfully
        if ($html === false) {
            throw new \Exception('Failed to load HTML content');
        }

        $table = "";

        foreach ($materiels as $materiel) {
            $table = $table. "<tr>
            <td>
                <p style='max-width:200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>
                    <strong>". $materiel->getNom() ."</strong>
                </p>
            </td>                                  
            <td>
              <label class='badge badge-info'>". $materiel->getCategorie() ."</label>
            </td>                                            
            <td>
              <label>". $materiel->getQte() ."</label>
            </td>     
        </tr>";
        }
        // Replace placeholders in HTML content with reservation data
        $html = str_replace('{row}', $table, $html);

        $dompdf = new Dompdf();

        // Load HTML into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Get PDF content
        $pdfContent = $dompdf->output();

        // Create response with PDF content
        $response = new Response($pdfContent);

        // Set response headers
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="materiel.pdf"');

        return $response;
    }
    
}

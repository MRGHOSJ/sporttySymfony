<?php

namespace App\Controller;

use App\Entity\gyms;
use App\Entity\SaleDeSport;
use App\Form\gymsType;
use App\Form\SaleDeSportType;
use App\Repository\SaleDeSportRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile; 
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\BuilderInterface;
use Knp\Component\Pager\PaginatorInterface;

class GymController extends AbstractController
{
    #[Route('/back/gyms', name: 'app_back_gyms')]
    public function index(Request $request,SaleDeSportRepository $SaleDeSportRepository,PaginatorInterface $paginator): Response
    {
        
        
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'idSalle');

        $sortBy = $request->query->get('sort_by', 'idSalle');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $SaleDeSportRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);
        $pagination = $paginator->paginate(
            $items,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('back/gyms/allgyms.html.twig',[
            "gyms"=>$items,
            "pagination"=>$pagination,
        ]); 
        
    }

    #[Route('/back/gyms/add', name: 'app_back_gyms_add')]
    public function addgyms  (Request $request, ManagerRegistry $manager , EntityManagerInterface $entityManager    ): Response
    {
        $gyms = new SaleDeSport();
        $form = $this->createForm(SaleDeSportType::class, $gyms);
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
                $gyms->setImage($nomFichier);
            }

            $entityManager->persist($gyms);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_gyms', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/gyms/formgyms.html.twig', [
            'gyms' => $gyms,
            'form' => $form,
        ]);
    }

    #[Route('/back/gyms/{id}/edit', name: 'update_gym')]
    public function edit(Request $request, ManagerRegistry $manager,SaleDeSportRepository $gymsRepository,int $id)
    {
        $gyms = $gymsRepository->find($id);
        $form = $this->createForm(SaleDeSportType::class, $gyms);
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

                // Stockage du nom du fichier dans l'entité Materiel
                $gyms->setImage($nomFichier);
            }
            $em=$manager->getManager();
            $em->persist($gyms);
            $em->flush();
            return $this->redirectToRoute('app_back_gyms');
        }

        return $this->render('back/gyms/formgyms.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/gyms/{id}/delete', name: 'delete_gym')]
    public function delete(int $id,ManagerRegistry $mr, SaleDeSportRepository $gymsRepository): Response
    {
        $gyms = $gymsRepository->find($id);
        $entityManager = $mr->getManager();
        $entityManager->remove($gyms);
        $entityManager->flush();

        return $this->redirectToRoute('app_back_gyms');
    }



    
    #[Route('/gyms', name: 'app_front_gyms')]
    public function gymsFront(SaleDeSportRepository $gymsRepository, BuilderInterface $qrBuilder): Response
    {
        $gyms = $gymsRepository->findAll();
        $qrCodes = [];
    
        foreach ($gyms as $item) {
            $qrData = json_encode([
                'lienvideo' => $item->getLienvideo()
            ]);
    
            $qrResult = $qrBuilder
                ->size(200)
                ->margin(20)
                ->data($qrData)
                ->build();
    
            $qrCode = $qrResult->getDataUri();
    
            $qrCodes[] = $qrCode;
        }
    
        return $this->render('front/gyms/pricing.html.twig', [
            'gyms' => $gyms,
            'qrCodes' => $qrCodes
        ]);
    }
    
    #[Route("/generate-pdf/gyms", name: "app_generate_pdf_gyms")]
    public function generatePdf(SaleDeSportRepository $SaleDeSportRepository): Response
    {
        // Retrieve reservation from the repository
        $gyms = $SaleDeSportRepository->findAll();

        // Check if reservation exists
        if (!$gyms) {
            throw $this->createNotFoundException('Reservation not found');
        }

        // Construct HTML content
        $htmlFilePath = $this->getParameter('kernel.project_dir') . '/public/pdf/reservation.html';
        $html = file_get_contents($htmlFilePath);

        // Check if HTML content was loaded successfully
        if ($html === false) {
            throw new \Exception('Failed to load HTML content');
        }

        $table = "";

        foreach ($gyms as $gym) {
            $table = $table. "<tr>
            <td>
                <p style='max-width:200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>
                    <strong>". $gym->getNomSalle() ."</strong>
                </p>
            </td>                                  
            <td>
              <label class='badge badge-info'>". $gym->getDescr() ."</label>
            </td>                                            
            <td>
              <label>". $gym->getNumSalle() ."</label>
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
        $response->headers->set('Content-Disposition', 'attachment; filename="gymList.pdf"');

        return $response;
    }
    
}

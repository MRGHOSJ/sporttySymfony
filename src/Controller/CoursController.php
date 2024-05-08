<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Cours;
use App\Form\CoursType;
use Symfony\Component\Mime\Email;
use App\Repository\CoursRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CoursController extends AbstractController
{
    #[Route('/back/cours', name: 'app_back_Cours')]
    public function index(Request $request,CoursRepository $CoursRepository,PaginatorInterface $paginator): Response
    {

        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'idCours');

        $sortBy = $request->query->get('sort_by', 'idCours');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $CoursRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        
        $pagination = $paginator->paginate(
            $items,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('back/Cours/allCours.html.twig',[
            "Cours"=>$items,
            "pagination"=>$pagination,
        ]);
    }

    #[Route('/back/Cours/add', name: 'app_back_Cours_add')]
    public function addCours  (Request $request, ManagerRegistry $manager,MailerInterface $mailer): Response
    {
        $Cours = new Cours();
        $form = $this->createForm(CoursType::class, $Cours);
        $form->handleRequest($request);
        dump('fff');
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($Cours);
            $em->flush();
        
         
            // Envoi de l'e-mail après la modification du cours
            $this->validateEmailCour($mailer);
            dump('hhhhh');

            return $this->redirectToRoute('app_back_Cours');
        }

        return $this->render('back/Cours/formCours.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/Cours/{id}/edit', name: 'update_Cours')]
    public function edit(Request $request, ManagerRegistry $manager,CoursRepository $CoursRepository,int $id)
    {
        $Cours = $CoursRepository->find($id);
        $form = $this->createForm(CoursType::class, $Cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($Cours);
            $em->flush();
            return $this->redirectToRoute('app_back_Cours');
        }

        return $this->render('back/Cours/formCours.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/Cours/{id}/delete', name: 'delete_Cours')]
    public function delete(Cours $Cours,ManagerRegistry $mr): Response
    {
        $entityManager = $mr->getManager();
        $entityManager->remove($Cours);
        $entityManager->flush();

        return $this->redirectToRoute('app_back_Cours');
    }



    
    #[Route('/Cours', name: 'app_front_Cours')]
    public function CoursFront(CoursRepository $CoursRepository): Response
    {
        return $this->render('front/Cours/pricing.html.twig',[
            'Cours'=>$CoursRepository->findAll(),
        ]);
    }
    

  
 
  
        
    
        #[Route('/back/pdf', name: 'generate_pdf_cours')]
        public function generatePdf(CoursRepository $CoursRepository): Response
        {
            // Retrieve reservation from the repository
            $courses = $CoursRepository->findAll();

            // Check if reservation exists
            if (!$courses) {
                throw $this->createNotFoundException('Reservation not found');
            }

            // Construct HTML content
            $htmlFilePath = $this->getParameter('kernel.project_dir') . '/public/pdf/cours.html';
            $html = file_get_contents($htmlFilePath);

            // Check if HTML content was loaded successfully
            if ($html === false) {
                throw new \Exception('Failed to load HTML content');
            }
            $coursAffichage = "";
            foreach ($courses as $course) {
                $coursAffichage .= '<div class="course">
                    <div class="course-details">
                        <h3>' . $course->getNom() . '</h3>
                        <p>Coach: ' . $course->getCoach() . '</p>
                        <p>Duree: ' . $course->getDuree() . '</p>
                        <p>Type: ' . $course->getType() . '</p>
                    </div>
                    <div class="course-price">' . $course->getPrix() . ' DT</div>
                </div>';
            }

            $html = str_replace('{cours}', $coursAffichage, $html);

            // Initialize Dompdf with options
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
            $response->headers->set('Content-Disposition', 'attachment; filename="cours.pdf"');

            return $response;
        }
    
      
     
        #[Route('/back/stat', name: 'stat')]
             public function showStatistics(CoursRepository $coursRepository): Response
            {
                // Récupérer les statistiques par type de réclamation
                $statistics = $coursRepository->countCoursByType();
        
                // Rendre la vue avec les statistiques
                return $this->render('back/index.html.twig', [
                    'statistics' => $statistics,
                ]);
            }




            #[Route('/validate_emailCour', name: 'validate_emailCour')]
            public function validateEmailCour( MailerInterface $mailer): Response
            {
                
            
                
            
               $message1 = (new Email())
                    ->from('dinagharbi893@gmail.com')
                    ->to('nawel.kaabi@esprit.tn')
                    ->subject('Hello')
                    ->text('You have added this Course');
            
                $mailer->send($message1);
            
                return $this->redirectToRoute('app_back_Cours');
               
            }

    }

    
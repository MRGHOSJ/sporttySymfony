<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Form\StockType;
use App\Repository\StockRepository;
use App\Service\MailService;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    #[Route('/back/stocks', name: 'app_back_Stock')]
    public function index(Request $request,StockRepository $StockRepository,PaginatorInterface $paginator): Response
    {

        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $StockRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        $pagination = $paginator->paginate(
            $items,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('back/Stock/allStock.html.twig',[
            "Stocks"=>$items,
            "pagination"=>$pagination,
        ]);
    }

    #[Route('/back/map', name: 'app_back_map')]
    public function Map(Request $request,StockRepository $StockRepository): Response
    {
        
        $stocks = $StockRepository->findAll();
        $markers = [];

        foreach ($stocks as $stock) {
            sscanf($stock->getCordonnet(), '%f, %f', $lat, $lng);  
            $markers[] = ['latitude' => $lat, 'longitude' => $lng];
        }

        return $this->render('back/map/index.html.twig', [
            'markers' => $markers,
        ]);    }

    #[Route('/back/stocks/add', name: 'app_back_Stock_add')]
    public function addStock  (Request $request, ManagerRegistry $manager): Response
    {
        $Stock = new Stock();
        $form = $this->createForm(StockType::class, $Stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($Stock);
            $em->flush();

            
            $mailService = new MailService(); 

            $recipient = 'bouzouitayassine@gmail.com';
            $subject = 'Stock Added';
            $htmlContent = $mailService->readHTMLFile('mail/notification/INFO.html');
            $htmlContent = str_replace("{Title}", "Stock Added", $htmlContent);
            $htmlContent = str_replace("{Description}", 
            "Nom: ".$Stock->getNom().   "<br>" .
            "Quantite: ".$Stock->getQuantite().   "<br>"             
            , $htmlContent);
            
            $mailService->sendMail($recipient, $subject, $htmlContent);

            
            return $this->redirectToRoute('app_back_Stock');
        }

        return $this->render('back/Stock/formStock.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/stocks/{id}/edit', name: 'update_Stock')]
    public function edit(Request $request, ManagerRegistry $manager,StockRepository $StockRepository,int $id)
    {
        $Stock = $StockRepository->find($id);
        $form = $this->createForm(StockType::class, $Stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($Stock);
            $em->flush();
            $mailService = new MailService(); 

            $recipient = 'bouzouitayassine@gmail.com';
            $subject = 'Stock Updated';
            $htmlContent = $mailService->readHTMLFile('mail/notification/INFO.html');
            $htmlContent = str_replace("{Title}", "Stock Updated", $htmlContent);
            $htmlContent = str_replace("{Description}", 
            "Nom: ".$Stock->getNom().   "<br>" .
            "Quantite: ".$Stock->getQuantite().   "<br>"             
            , $htmlContent);
            
            $mailService->sendMail($recipient, $subject, $htmlContent);

            return $this->redirectToRoute('app_back_Stock');
        }

        return $this->render('back/Stock/formStock.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/stocks/{id}/delete', name: 'delete_Stock')]
    public function delete(StockRepository $StockRepository,int $id,ManagerRegistry $mr): Response
    {
        $Stock = $StockRepository->find($id);
        $entityManager = $mr->getManager();
        $entityManager->remove($Stock);
        $entityManager->flush();
        $mailService = new MailService(); 

        $recipient = 'bouzouitayassine@gmail.com';
        $subject = 'Stock Deleted';
        $htmlContent = $mailService->readHTMLFile('mail/notification/ALERT.html');
        $htmlContent = str_replace("{Title}", "Stock Deleted", $htmlContent);
        $htmlContent = str_replace("{Description}", 
        "Nom: ".$Stock->getNom().   "<br>" .
        "Quantite: ".$Stock->getQuantite().   "<br>"             
        , $htmlContent);
        
        $mailService->sendMail($recipient, $subject, $htmlContent);

        return $this->redirectToRoute('app_back');
    }



    
    #[Route('/stocks', name: 'app_front_Stock')]
    public function StockFront(StockRepository $StockRepository): Response
    {
        return $this->render('front/Stock/pricing.html.twig',[
            'Stocks'=>$StockRepository->findAll(),
        ]);
    }

    
    #[Route('/admin/stocks/pdf', name: 'app_back_stocks_pdf')]
    public function generatePdf(StockRepository $StockRepository): Response
    {
        $stock = $StockRepository->findAll();

        if (!$stock) {
            throw $this->createNotFoundException('Stock not found');
        }

        // Construct HTML content
        $htmlFilePath = $this->getParameter('kernel.project_dir') . '/public/pdf/stock.html';
        $html = file_get_contents($htmlFilePath);

        // Check if HTML content was loaded successfully
        if ($html === false) {
            throw new \Exception('Failed to load HTML content');
        }

        // Replace placeholders in HTML content with reservation data
        $html = str_replace('{today}', date("m/d/y"), $html);

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

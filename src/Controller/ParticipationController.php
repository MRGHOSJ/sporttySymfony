<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Entity\Participation;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ParticipationRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
<<<<<<< Updated upstream
=======
use Dompdf\Dompdf;
>>>>>>> Stashed changes

class ParticipationController extends AbstractController
{
    #[Route('/participation', name: 'app_participation')]
    public function index(): Response
    {     
        return $this->render('participation/index.html.twig', [
            'controller_name' => 'ParticipationController',
        ]);
    }

   
   /* #[Route('/participate/{id}', name: 'participate')]
    public function participate(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        if (!$user) {
            // Rediriger vers la page de connexion si aucun utilisateur n'est connecté
            return $this->redirectToRoute('login');
        }
    
        // Récupérer l'événement par son ID
        $event = $entityManager->getRepository(Evenements::class)->find($id);
        if (!$event) {
            throw $this->createNotFoundException('No event found for id ' . $id);
        }
    
        // Vérifier si l'utilisateur participe déjà à l'événement
        $participationExist = $entityManager->getRepository(Participation::class)->findOneBy(['user' => $user, 'event' => $event]);
        if ($participationExist) {
            $this->addFlash('error', 'You have already participated in this event.');
            return $this->redirectToRoute('moreEvent', ['id' => $id]);
        }
    
        // Créer une nouvelle participation
        $participation = new Participation();
        $participation->setUser($user);
        $participation->setEvenement($event);
    
        // Ajouter la participation à la base de données
        $entityManager->persist($participation);
    
        // Mettre à jour le nombre de participants de l'événement
        $event->setNbrPar($event->getNbrPar() + 1);
    
        // Sauvegarder les modifications
        $entityManager->flush();
    
        // Ajouter un message de succès et rediriger vers la page de détail de l'événement
        $this->addFlash('success', 'You have successfully participated in the event.');
        return $this->redirectToRoute('moreEvent', ['id' => $id,
             ]);
    }*/
    #[Route('/participate/{id}', name: 'participate')]
public function participate(int $id, EntityManagerInterface $entityManager, Request $request): Response
{
    // Récupérer l'utilisateur connecté
    $user = $this->getUser();
    if (!$user) {
        // Rediriger vers la page de connexion si aucun utilisateur n'est connecté
        return $this->redirectToRoute('login');
    }

    // Récupérer l'événement par son ID
    $event = $entityManager->getRepository(Evenements::class)->find($id);
    if (!$event) {
        throw $this->createNotFoundException('No event found for id ' . $id);
    }

    // Créer une nouvelle participation
    $participation = new Participation();
    $participation->setUser($user);
    $participation->setEvenement($event);

    // Ajouter la participation à la base de données
    $entityManager->persist($participation);

    // Mettre à jour le nombre de participants de l'événement
    $event->setNbrPar($event->getNbrPar() + 1);

    // Sauvegarder les modifications
    $entityManager->flush();

    // Ajouter un message de succès et rediriger vers la page de détail de l'événement
    $this->addFlash('success', 'You have successfully participated in the event.');
<<<<<<< Updated upstream
    return $this->redirectToRoute('moreEvent', ['id' => $id]);
}

#[Route('/back/showPart', name: 'listPart')]
public function showPart(ParticipationRepository $participationRepository, Request $request): Response
{     
    $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $participationRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);
    // Retourner la réponse avec la vue
    return $this->render('back/allParticipants.html.twig', [
        'participations' => $items,
    ]);
}
=======
   // return $this->redirectToRoute('moreEvent', ['id' => $id]);
    // Générer le PDF et rediriger vers la page de téléchargement du PDF
    return $this->redirectToRoute('app_front_participation_pdf', ['id' => $participation->getId()]);
}



#[Route('/participation/pdf/{id}', name: 'app_front_participation_pdf')]
    public function generatePdf(int $id,ParticipationRepository $participationRepository): Response
    {
        // Retrieve reservation from the repository
    $participation = $participationRepository->find($id);

    // Check if reservation exists
    if (!$participation) {
        throw $this->createNotFoundException('Participation not found');
    }

    // Construct HTML content
    $htmlFilePath = $this->getParameter('kernel.project_dir') . '/public/pdf/ticket.html';
    $html = file_get_contents($htmlFilePath);

    // Check if HTML content was loaded successfully
    if ($html === false) {
        throw new \Exception('Failed to load HTML content');
    }

    // Replace placeholders in HTML content with reservation data
    $html = str_replace('{eventDate}', $participation->getEvenement()->getDateEvent("m/d/y")->format("d/m/y"), $html);
    $html = str_replace('{eventTime}', $participation->getEvenement()->getHeureEvent("H:i")->format( "H:i"), $html);
 
    $html = str_replace('{eventName}', $participation->getEvenement()->getNomEvent(), $html);
    $html = str_replace('{userName}',  $participation->getUser()->getNom(), $html);

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
    $response->headers->set('Content-Disposition', 'attachment; filename="ticket.pdf"');

    return $response;
    }

    #[Route('/back/showPart', name: 'listPart')]
    public function showPart(ParticipationRepository $participationRepository, Request $request): Response
    {     
        $searchQuery = $request->query->get('search');
            $searchBy = $request->query->get('search_by', 'id');
    
            $sortBy = $request->query->get('sort_by', 'id');
            $sortOrder = $request->query->get('sort_order', 'asc');
    
            $items = $participationRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);
        // Retourner la réponse avec la vue
        return $this->render('back/allParticipants.html.twig', [
            'participations' => $items,
        ]);
    }

>>>>>>> Stashed changes

}

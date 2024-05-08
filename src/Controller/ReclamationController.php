<?php

namespace App\Controller;

<<<<<<< Updated upstream
use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationType;
use App\Form\ReclamationFrontType;

use App\Repository\ReclamationRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
=======
use App\Entity\User;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Symfony\Component\Mime\Email;

use App\Form\ReclamationFrontType;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;
>>>>>>> Stashed changes


class ReclamationController extends AbstractController
{
<<<<<<< Updated upstream
    #[Route('/admin/reservation', name: 'app_back_reservation')]
    public function index(Request $request,ReclamationRepository $reclamationRepository): Response
    {
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $reclamationRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);


       

        return $this->render('back/pages/reservation/index.html.twig', [
            "items" => $items,
            
        ]);
    }
=======
    /*
    #[Route('/validate-email', name: 'validate_email')]
    public function validateEmail(Request $request, MailerInterface $mailer)  : Response    {
       
       
        $message = (new Email())
            ->from('dinagharbi893@gmail.com')
            ->to('nour.allani@esprit.tn')
            ->subject('Hello')
            ->text('Bonjour12');
    
        $mailer->send($message);
    
        return $this->redirectToRoute('app_back');
     

        dump('code23'); 

    
}*/


    
    #[Route('/back/stat', name: 'stat')]
     public function showStatistics(ReclamationRepository $reclamationRepository): Response
    {
        // Récupérer les statistiques par type de réclamation
        $statistics = $reclamationRepository->countReclamationsByType();

        // Rendre la vue avec les statistiques
        return $this->render('back/index.html.twig', [
            'statistics' => $statistics,
        ]);
    }
   
>>>>>>> Stashed changes
   /* #[Route("/generate-pdf", name: "app_generate_pdf")]
    public function generatePdf(Request $request): Response
    {
        // Récupérer la liste des matériels depuis la base de données
        $materiels = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();

        // Créer une instance de Dompdf
        $dompdf = new Dompdf();

        // Construction du contenu HTML à partir du template twig
        $html = $this->renderView('back/materiel/pdf.html.twig', [
            'materiels' => $materiels,
        ]);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // (Optionnel) Paramètres de la mise en page
        $dompdf->setPaper('A4', 'portrait');

        // Rendu du PDF
        $dompdf->render();

        // Générer le nom du fichier PDF
        $pdfFileName = 'liste_materiels.pdf';

        // Créer une réponse avec le contenu du PDF
        $response = new Response($dompdf->output());

        // Ajouter les en-têtes pour indiquer au navigateur de télécharger le fichier
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $pdfFileName . '"');

        // Retourner la réponse
        return $response;
    }*/
<<<<<<< Updated upstream
    #[Route('/back/reclamation/stat', name: 'reclamation_stats')]
    public function reclamationStats(ReclamationRepository $reclamationRepository): Response
    {
        // Récupérer les données des réclamations par nom
        $reclamationCounts = $reclamationRepository->getReclamationCountByNom();
        dump($reclamationCounts);
    
        // Préparer les données pour le graphique
        $chartData = [];
        foreach ($reclamationCounts as $reclamationCount) {
            $chartData[] = [$reclamationCount['nom'], $reclamationCount['count']];
        }
    
        // Rendre la vue Twig avec les données du graphique
        return $this->render('back/reclamation/stat.html.twig', [
            'chartData' => $chartData,
        ]);
    }
=======
    
>>>>>>> Stashed changes

    
    #[Route('/back/reclamation/showRec', name: 'listRec')]
    public function show(ReclamationRepository $reclamationRepository,Request $request): Response
    {  $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $reclamationRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        return $this->render('back/reclamation/allRec.html.twig', [
            'reclamations' => $items,
        ]);
    }
<<<<<<< Updated upstream

=======
    
>>>>>>> Stashed changes
    #[Route('/front/reclamation/new', name: 'new_reclamation')]
    public function new(Request $request, ManagerRegistry $mr): Response
    {
        // Créez une nouvelle instance de l'entité Reclamation
        $reclamation = new Reclamation();

        // Définissez les valeurs par défaut pour les champs user, date et statut
        $reclamation->setUser( $this->getUser());// Définissez l'utilisateur actuel comme l'utilisateur de la réclamation
        $reclamation->setDate(new \DateTime()); // Définissez la date actuelle comme date de la réclamation
        $reclamation->setStatut('Pending'); // Définissez le statut par défaut

        // Créez le formulaire en utilisant le type de formulaire ReclamationFrontType
        $form = $this->createForm(ReclamationFrontType::class, $reclamation);

        // Gérez la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
<<<<<<< Updated upstream
=======
            // Récupérer la description de la réclamation depuis le formulaire
            $description = $reclamation->getDescription();

            // Censurer les mots inappropriés dans la description
            $censoredDescription = $this->censorBadWords($description);

            // Enregistrer la description censurée dans la réclamation
            $reclamation->setDescription($censoredDescription);
>>>>>>> Stashed changes
            // Si le formulaire est soumis et valide, enregistrez la réclamation en base de données
            $entityManager = $mr->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();

            // Redirigez l'utilisateur vers une autre page après avoir soumis la réclamation
            return $this->redirectToRoute('new_reclamation'); // Remplacez 'home' par le nom de votre route cible
        }

        // Rendez le formulaire dans votre template Twig
        return $this->render('front/reclamation/contact.html.twig', [
            'form' => $form->createView(),
            'reclamation' => $reclamation,
        ]);
    }
<<<<<<< Updated upstream

=======
    private function censorBadWords($text)
    {
        $badWords = ['inappropriate', 'insult', 'offensive', 'forbidden'];

        // Replace inappropriate words with asterisks (*), ignoring case
    foreach ($badWords as $word) {
        $replacement = str_repeat('*', mb_strlen($word)); // Creates a replacement of the same length as the word
        // Use case-insensitive regular expression to replace whole words
        $text = preg_replace('/\b' . preg_quote($word, '/') . '\b/i', $replacement, $text);
    }

        return $text;
    }

/*
>>>>>>> Stashed changes
#[Route('/back/reclamation/{id}/edit', name: 'editRec')]
public function edit(Request $request, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager, int $id): Response
{
    $reclamation = $reclamationRepository->find($id);

    if (!$reclamation) {
        throw $this->createNotFoundException('No reclamation found for id '.$id);
    }
   // Récupérez la valeur actuelle du champ "statut"
   $currentStatut = $reclamation->getStatut();
    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $reclamation->setStatut($currentStatut);
        $entityManager->flush();
        return $this->redirectToRoute('listRec');
    }

    return $this->render('back/reclamation/editRec.html.twig', [
        'form' => $form->createView(),
        'reclamation' => $reclamation,
    ]);
}
<<<<<<< Updated upstream
=======
*/
#[Route('/back/reclamation/{id}/edit', name: 'editRec')]
public function edit(Request $request, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager, MailerInterface $mailer, int $id): Response
{
    $reclamation = $reclamationRepository->find($id);

    if (!$reclamation) {
        throw $this->createNotFoundException('No reclamation found for id '.$id);
    }

    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrement des modifications
        $entityManager->flush();

        // Préparation et envoi de l'email
        $responseContent = $form->get('reponse')->getData(); // Assurez-vous que le champ est correctement nommé dans votre ReclamationType
        $email = (new Email())
        ->from('dinagharbi893@gmail.com')
        ->to('nour.allani@esprit.tn')
            ->subject('Réponse à votre réclamation')
            ->text('Réponse à votre réclamation : ' . $responseContent);

        $mailer->send($email);

        // Redirection
        return $this->redirectToRoute('listRec');
    }

    return $this->render('back/reclamation/editRec.html.twig', [
        'form' => $form->createView(),
        'reclamation' => $reclamation,
    ]);
}
>>>>>>> Stashed changes

   
    #[Route('/back/reclamation/{id}', name: 'deleteRec')]
    public function deleteRec(ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager,int $id): Response
{
    $reclamation = $reclamationRepository->find($id);

    if (!$reclamation) {
        throw $this->createNotFoundException('No reclamation found for id '.$id);
    }


    $entityManager->remove($reclamation);
    $entityManager->flush();

    return $this->redirectToRoute('listRec');
}
}

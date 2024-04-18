<?php

namespace App\Controller;

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


class ReclamationController extends AbstractController
{
  
    #[Route('/back/reclamation/showRec', name: 'listRec')]
    public function show(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('back/reclamation/allRec.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

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

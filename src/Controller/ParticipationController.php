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
    return $this->redirectToRoute('moreEvent', ['id' => $id]);
}

#[Route('/back/showPart', name: 'listPart')]
public function showPart(ParticipationRepository $participationRepository): Response
{
    // Récupérer les participations depuis le repository
    $participations = $participationRepository->findAll();

    // Utiliser dump pour afficher les données
    dump($participations);

    // Retourner la réponse avec la vue
    return $this->render('back/allParticipants.html.twig', [
        'participations' => $participations,
    ]);
}

}

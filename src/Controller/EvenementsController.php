<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Evenements;
use App\Entity\Participation;
use App\Form\EvenementsType;
use App\Repository\EvenementsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;



class EvenementsController extends AbstractController
{   
   
<<<<<<< Updated upstream
    #[Route('/front/evenements/search', name: 'evenement_search')]
public function search(Request $request, EvenementsRepository $repository): JsonResponse
{
    // Récupérer les critères de recherche depuis la requête
    $keyword = $request->query->get('keyword');
    // Autres critères de recherche si nécessaire

    // Effectuer la recherche dans la base de données
    $results = $repository->search($keyword);
    // Autres critères de recherche si nécessaire

    // Retourner les résultats au format JSON
    return new JsonResponse($results);
}




=======
>>>>>>> Stashed changes
    #[Route('/showFront', name: 'EventsF')]
    public function showF(EvenementsRepository $evenementsRepository): Response
    {
        
        return $this->render('front/index.html.twig', [
            'events' => $evenementsRepository->findNewEvents(),
        ]);
    }

  
    #[Route('/front/evenements/moreEvent', name: 'moreEvent')]
public function showMore(EvenementsRepository $evenementsRepository, EntityManagerInterface $entityManager,Request $request): Response
{    // Récupérer la catégorie à filtrer depuis la requête
    $category = $request->query->get('categorieEvent');

    // Si une catégorie est spécifiée dans la requête, filtrez les événements par cette catégorie
    if ($category) {
        $events = $evenementsRepository->findByCategory($category);
    }else{
    $events = $evenementsRepository->findAll();
        }
    // Récupérer l'utilisateur connecté
    $user = $this->getUser();

    // Vérifier la participation de l'utilisateur à chaque événement
    $participationExists = [];
    foreach ($events as $event) {
        $participationExist = $entityManager->getRepository(Participation::class)->findOneBy(['user' => $user, 'event' => $event]);
        $participationExists[$event->getIdEvent()] = $participationExist !== null;
    }
 // Récupérer les nombres d'événements pour chaque catégorie
 $categoryCounts = $evenementsRepository->countEventsByCategory();

    return $this->render('front/evenements/viewMore.html.twig', [
        'events' => $events,
        'participationExists' => $participationExists,
        'categoryCounts' => $categoryCounts,
    ]);
}


    #[Route('/back/evenements/show', name: 'listEvents')]
    public function show(EvenementsRepository $evenementsRepository, Request $request): Response
    {
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'idEvent');

        $sortBy = $request->query->get('sort_by', 'idEvent');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $evenementsRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        return $this->render('back/evenements/allEvents.html.twig', [
            'evenements' => $items,
        ]);
    }

    #[Route('/back/evenements/new', name: 'newEvent')]
    public function new(Request $request, ManagerRegistry $mr): Response
    {
        $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager=$mr->getManager();
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('listEvents');
        }
    
        return $this->render('back/evenements/newEvent.html.twig', [
           
            'form' => $form->createView(),
            'evenement' => $evenement,
        ]);

    }

    
   #[Route('/back/evenements/{idEvent}/edit', name: 'editEvent')]
public function edit(Request $request, EvenementsRepository $evenementsRepository, EntityManagerInterface $entityManager, int $idEvent): Response
{
    $evenement = $evenementsRepository->find($idEvent);

    if (!$evenement) {
        throw $this->createNotFoundException('No event found for id '.$idEvent);
    }
    $categorieEventValue = $evenement->getCategorieEvent();

    $form = $this->createForm(EvenementsType::class, $evenement);
   
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        return $this->redirectToRoute('listEvents');
    }

    return $this->render('back/evenements/editEvent.html.twig', [
        'form' => $form->createView(),
        'evenement' => $evenement,
    ]);
}

    #[Route('/back/evenements/{idEvent}', name: 'deleteEvent')]
public function deleteEvent(EvenementsRepository $evenementsRepository, EntityManagerInterface $entityManager, int $idEvent): Response
{
    $evenement = $evenementsRepository->find($idEvent);

    if (!$evenement) {
        throw $this->createNotFoundException('No event found for id '.$idEvent);
    }

    $entityManager->remove($evenement);
    $entityManager->flush();

    return $this->redirectToRoute('listEvents');
}



}

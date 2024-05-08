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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class EvenementsController extends AbstractController
{   
   
    #[Route('/showFront', name: 'EventsF')]
    public function showF(Security $security,EvenementsRepository $evenementsRepository): Response
    {
        $user = $security->getUser();

        return $this->render('front/evenements/index.html.twig', [
            'events' => $evenementsRepository->findNewEvents(),
            'user'=>$user
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
    public function show(EvenementsRepository $evenementsRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'idEvent');

        $sortBy = $request->query->get('sort_by', 'idEvent');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $evenementsRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);
        
        $pagination = $paginator->paginate(
            $items,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('back/evenements/allEvents.html.twig', [
            'evenements' => $items,
            "pagination"=>$pagination,
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

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ParticipationRepository;
<<<<<<< Updated upstream

class CalendarController extends AbstractController
{
   /* #[Route('/calendar', name: 'app_calendar')]
    public function index(): Response
    {
        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }*/
    #[Route('/calendar', name: 'calendar')]
    public function index(ParticipationRepository $participationRepository): Response
=======
use App\Repository\EvenementsRepository;

class CalendarController extends AbstractController
{
   
    #[Route('/calendar', name: 'calendar')]
   /* public function index(ParticipationRepository $participationRepository): Response
>>>>>>> Stashed changes
    {
        // Récupérer les événements auxquels l'utilisateur a participé
        $participatedEvents = $participationRepository->findEventsByUser($this->getUser());

        // Transmettre les événements à la vue
        return $this->render('calendar/calendrier.html.twig', [
            'participatedEvents' => $participatedEvents,
        ]);
<<<<<<< Updated upstream
=======
    }*/

    public function index(ParticipationRepository $participationRepository, EvenementsRepository $evenementRepository): Response
    {
        // Récupérer tous les événements
        $allEvents = $evenementRepository->findAll();

        // Récupérer les événements auxquels l'utilisateur a participé
        $participatedEvents = $participationRepository->findEventsByUser($this->getUser());

        // Créer un tableau pour stocker les IDs des événements auxquels l'utilisateur a participé
        $participatedEventIds = [];
        foreach ($participatedEvents as $participation) {
            $participatedEventIds[] = $participation->getEvenement()->getIdEvent();
        }

        // Transmettre les événements à la vue
        return $this->render('calendar/calendrier.html.twig', [
            'events' => $allEvents,
            'participatedEventIds' => $participatedEventIds,
        ]);
>>>>>>> Stashed changes
    }
}

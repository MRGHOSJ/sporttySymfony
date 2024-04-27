<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ParticipationRepository;

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
    {
        // Récupérer les événements auxquels l'utilisateur a participé
        $participatedEvents = $participationRepository->findEventsByUser($this->getUser());

        // Transmettre les événements à la vue
        return $this->render('calendar/calendrier.html.twig', [
            'participatedEvents' => $participatedEvents,
        ]);
    }
}

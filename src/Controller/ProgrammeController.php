<?php

namespace App\Controller;

use App\Entity\Programme;
<<<<<<< Updated upstream
use App\Form\Programme1Type;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProgrammeController extends AbstractController
{
    #[Route('/show1', name: 'app_programme_index')]
    public function index(ProgrammeRepository $programmeRepository): Response
    {
        return $this->render('/back/programme/index.html.twig', [
            'programmes' => $programmeRepository->findAll(),
        ]);
    }


 

    #[Route('/back/programme/new1', name: 'app_programme_new')]
    public function new(Request $request, ManagerRegistry $pr): Response
    {
        $programme = new Programme();
        $form = $this->createForm(Programme1Type::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager=$pr->getManager();
            $entityManager->persist($programme);
            $entityManager->flush();

            return $this->redirectToRoute('app_programme_show');
        }

        return $this->renderForm('back/programme/new.html.twig', [
            'programme' => $programme,
            'form' => $form,
        ]);
    }

    #[Route('/back/programme/show1', name: 'app_programme_show')]
    public function show(ProgrammeRepository $programmeRepository): Response
    {
        return $this->render('back/programme/show.html.twig', [
            'programme' =>$programmeRepository ->findAll(),
        ]);
    }
/*
    #[Route('/{id}/edit', name: 'app_programme_edit')]
    public function edit(Request $request, Programme $programme, ManagerRegistry $pr): Response
    {
        $form = $this->createForm(Programme1Type::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager=$pr->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_programme_index');
        }

        return $this->renderForm('programme/edit.html.twig', [
            'programme' => $programme,
            'form' => $form,
        ]);
    }
*/

#[Route('/back/programme/{id}/edit', name: 'app_programme_edit')]
public function edit(Request $request, ProgrammeRepository $programmeRepository, EntityManagerInterface $entityManager, int $id): Response
{
    $programme = $programmeRepository->find($id);

    if (!$programme) {
        throw $this->createNotFoundException('No event found for id '.$id);
    }
   

    $form = $this->createForm(Programme1Type::class, $programme);
  
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        return $this->redirectToRoute('app_programme_show');
    }

    return $this->render('back/programme/edit.html.twig', [
        'form' => $form->createView(),
        'programme' => $programme,
    ]);
}

#[Route('/back/programme/{id}', name: 'app_programme_delete')]
public function deleteEvent(ProgrammeRepository $programmeRepository, EntityManagerInterface $entityManager, int $id): Response
{
    $programme = $programmeRepository->find($id);

    if (!$programme) {
        throw $this->createNotFoundException('No event found for id '.$id);
    }

    $entityManager->remove($programme);
    $entityManager->flush();

    return $this->redirectToRoute('app_programme_show');
}
=======
use App\Form\ProgrammeType;

use App\Repository\ProgrammeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController
{
    #[Route('/back/programmes', name: 'app_back_Programme')]
    public function index(Request $request,ProgrammeRepository $ProgrammeRepository): Response
    {

        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $ProgrammeRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        return $this->render('back/programmes/allprogramme.html.twig',[
            "programmes"=>$items
        ]);
    }

    #[Route('/back/programmes/add', name: 'app_back_Programme_add')]
    public function addProgramme  (Request $request, ManagerRegistry $manager): Response
    {
        $Programme = new Programme();
        $form = $this->createForm(ProgrammeType::class, $Programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($Programme);
            $em->flush();
            return $this->redirectToRoute('app_back_Programme');
        }

        return $this->render('back/programmes/formProgramme.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/programmes/{id}/edit', name: 'update_Programme')]
    public function edit(Request $request, ManagerRegistry $manager,ProgrammeRepository $ProgrammeRepository,int $id)
    {
        $Programme = $ProgrammeRepository->find($id);
        $form = $this->createForm(ProgrammeType::class, $Programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($Programme);
            $em->flush();
            return $this->redirectToRoute('app_back_Programme');
        }

        return $this->render('back/programmes/formProgramme.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/programmes/{id}/delete', name: 'delete_Programme')]
    public function delete(Programme $Programme,ManagerRegistry $mr): Response

    {
       
        $entityManager = $mr->getManager();
        $entityManager->remove($Programme);
        $entityManager->flush();
        var_dump($Programme);
        return $this->redirectToRoute('app_back_Programme');
    


    }
    
    #[Route('/programmes', name: 'app_front_Programme')]
    public function ProgrammeFront(Request $request,ProgrammeRepository $ProgrammeRepository): Response
    {
        
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $ProgrammeRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        return $this->render('front/Programme/pricing.html.twig',[
            'programmes'=>$items
        ]);
    }


/*
    #[Route('/front/Programme/sendSms/{id}', name: 'app_front_Programme_sendSms')]
    public function sendSMS(int $id, ProgrammeRepository $programmeRepository): Response
    {
        $programme = $programmeRepository->find($id);

        $recipient = '+21651252843';
        $message = 'Reminder you have a reservation at Program: ';

        $twilioService = new TwilioService('AC7e7550dbac39a38fa598c162d7d14166', '4dedcfbb5cbf770959e024fecc70afbc', '+13375141925');
        $isSent = $twilioService->sendSMS($recipient, $message);

        if ($isSent) {
            return $this->redirectToRoute('app_front_Programme');
        } else {
            return new Response('Failed to send SMS.');
        }*/
    }

    
>>>>>>> Stashed changes
}

<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Form\ProgrammeType;
use App\service\TwilioService;
use App\Repository\ProgrammeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController
{
    #[Route('/back/programmes', name: 'app_back_Programme')]
    public function index(Request $request,ProgrammeRepository $ProgrammeRepository,PaginatorInterface $paginator): Response
    {

        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'id');

        $sortBy = $request->query->get('sort_by', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $ProgrammeRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        
        $pagination = $paginator->paginate(
            $items,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('back/programmes/allprogramme.html.twig',[
            "programmes"=>$items,
            "pagination"=>$pagination,
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
        }
    }

    
}

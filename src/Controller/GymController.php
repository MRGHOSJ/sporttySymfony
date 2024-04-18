<?php

namespace App\Controller;

use App\Entity\gyms;
use App\Entity\SaleDeSport;
use App\Form\gymsType;
use App\Form\SaleDeSportType;
use App\Repository\SaleDeSportRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GymController extends AbstractController
{
    #[Route('/back/gyms', name: 'app_back_gyms')]
    public function index(SaleDeSportRepository $SaleDeSportRepository): Response
    {

        return $this->render('back/gyms/allgyms.html.twig',[
            "gyms"=>$SaleDeSportRepository->findAll(),
        ]); 
    }

    #[Route('/back/gyms/add', name: 'app_back_gyms_add')]
    public function addgyms  (Request $request, ManagerRegistry $manager): Response
    {
        $gyms = new SaleDeSport();
        $form = $this->createForm(SaleDeSportType::class, $gyms);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($gyms);
            $em->flush();
            return $this->redirectToRoute('app_back_gyms');
        }

        return $this->render('back/gyms/formgyms.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/gyms/{id}/edit', name: 'update_gym')]
    public function edit(Request $request, ManagerRegistry $manager,SaleDeSportRepository $gymsRepository,int $id)
    {
        $gyms = $gymsRepository->find($id);
        $form = $this->createForm(SaleDeSportType::class, $gyms);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($gyms);
            $em->flush();
            return $this->redirectToRoute('app_back_gyms');
        }

        return $this->render('back/gyms/formgyms.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/gyms/{id}/delete', name: 'delete_gym')]
    public function delete(SaleDeSport $gyms,ManagerRegistry $mr): Response
    {
        $entityManager = $mr->getManager();
        $entityManager->remove($gyms);
        $entityManager->flush();

        return $this->redirectToRoute('app_back');
    }



    
    #[Route('/gyms', name: 'app_front_gyms')]
    public function gymsFront(SaleDeSportRepository $gymsRepository): Response
    {
        return $this->render('front/gyms/pricing.html.twig',[
            'gyms'=>$gymsRepository->findAll(),
        ]);
    }
    
}

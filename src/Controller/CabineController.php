<?php

namespace App\Controller;

use App\Entity\Cabine;
use App\Form\CabineType;
use App\Repository\CabineRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CabineController extends AbstractController
{
    #[Route('/back/cabines', name: 'app_back_cabine')]
    public function index(CabineRepository $CabineRepository): Response
    {

        return $this->render('back/cabines/allcabine.html.twig',[
            "cabines"=>$CabineRepository->findAll(),
        ]);
    }

    #[Route('/back/cabines/add', name: 'app_back_cabine_add')]
    public function addcabine  (Request $request, ManagerRegistry $manager): Response
    {
        $cabine = new Cabine();
        $form = $this->createForm(CabineType::class, $cabine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($cabine);
            $em->flush();
            return $this->redirectToRoute('app_back_cabine');
        }

        return $this->render('back/cabines/formcabine.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/cabines/{id}/edit', name: 'update_cabine')]
    public function edit(Request $request, ManagerRegistry $manager,CabineRepository $cabineRepository,int $id)
    {
        $cabine = $cabineRepository->find($id);
        $form = $this->createForm(CabineType::class, $cabine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($cabine);
            $em->flush();
            return $this->redirectToRoute('app_back_cabine');
        }

        return $this->render('back/cabines/formcabine.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/back/cabines/{id}/delete', name: 'delete_cabine')]
    public function delete(Cabine $cabine,ManagerRegistry $mr): Response
    {
        $entityManager = $mr->getManager();
        $entityManager->remove($cabine);
        $entityManager->flush();

        return $this->redirectToRoute('app_back');
    }



    
    #[Route('/gyms/{id}/cabine', name: 'app_front_cabine')]
    public function cabineFront(CabineRepository $cabineRepository,int $id): Response
    {
        $cabines = $cabineRepository->findBy(['idSalle' => $id]);
        return $this->render('front/cabine/pricing.html.twig',[
            'cabines'=>$cabines,
        ]);
    }
    
}

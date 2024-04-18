<?php

namespace App\Controller;

use App\Entity\Programme;
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
}

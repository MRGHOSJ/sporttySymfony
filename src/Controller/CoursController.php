<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cours;
use App\Form\Cours1Type;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\Request;



class CoursController extends AbstractController
{
    #[Route('/show', name: 'app_cours_index')]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('back/cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }

    #[Route('/showP', name: 'CoursP')]
    public function showP(CoursRepository $coursRepository): Response
    {
        return $this->render('front/pages/index.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }


  

    #[Route('/back/cours/new', name: 'app_cours_new')]
    public function new(Request $request,  ManagerRegistry $cr ): Response
    {
        $cour = new Cours();
        $form = $this->createForm(Cours1Type::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
              // Récupération de l'image
              $imageFile = $form->get('image')->getData();

              // Vérification et traitement de l'image
              if ($imageFile) {
                  $nomFichier = md5(uniqid()) . '.' . $imageFile->guessExtension();
  
                  // Déplacement du fichier vers le dossier approprié
                  $imageFile->move(
                      $this->getParameter('dossier_images'), // Paramètre défini dans config/services.yaml
                      $nomFichier
                  );
  
                  // Stockage du nom du fichier dans l'entité cours
                  $cour->setImage($nomFichier);
              }
  
            $entityManager=$cr->getManager();
            $entityManager->persist($cour);
            $entityManager->flush();

            return $this->redirectToRoute('app_cours_show');
        }

        return $this->renderForm('/back/cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }
    


    #[Route('/back/cours/show', name: 'app_cours_show')]
    public function show(CoursRepository $cour): Response
    {
        return $this->render('back/cours/show.html.twig', [
            'cours' => $cour->findAll(),
        ]);
    }

    #[Route('/{idCours}/edit', name: 'app_cours_edit')]
    public function edit(Request $request, Cours $cour, ManagerRegistry $cr): Response
    {
        $form = $this->createForm(Cours1Type::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             // Récupération de l'image
             $imageFile = $form->get('image')->getData();

             // Vérification et traitement de l'image
             if ($imageFile) {
                 $nomFichier = md5(uniqid()) . '.' . $imageFile->guessExtension();
 
                 // Déplacement du fichier vers le dossier approprié
                 $imageFile->move(
                     $this->getParameter('dossier_images'), // Paramètre défini dans config/services.yaml
                     $nomFichier
                 );
 
                 // Stockage du nom du fichier dans l'entité cours
             }
            $entityManager=$cr->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_cours_show');
        }

        return $this->renderForm('back/cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form ,
        ]);
    }

    #[Route('/{idCours}', name: 'app_cours_delete')]
    public function delete(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {
      
            $entityManager->remove($cour);
            $entityManager->flush();
        

        return $this->redirectToRoute('app_cours_show');
    }

}

<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\AbonnementUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AbonnementType;
use Symfony\Component\HttpFoundation\Request;

class AbonnementController extends AbstractController
{

    #[Route('/back/UserAbonnement/abonnements', name: 'back_abonnement')]
    public function abonnemets(): Response
    {  dump('test1');
        $abonnemennts = $this->getDoctrine()->getRepository(Abonnement::class)->createQueryBuilder('a')
        ->select('a.id','a.type', 'a.prix', 'a.description')
        ->getQuery()
        ->getResult();
        dump('test1');
    return $this->render('back/UserAbonnement/abonnements.html.twig', ['abonnements' => $abonnemennts]);

    }

    #[Route('/abonnements', name: 'abonnements')]
    public function listAbonnements(): Response
    {
        // Récupérer tous les abonnements depuis la base de données
        $abonnements = $this->getDoctrine()->getRepository(Abonnement::class)->findAll();
    
        // Rendre la vue en passant les abonnements
        return $this->render('abonnement/list.html.twig', [
            'abonnements' => $abonnements,
        ]);
    }
    

    #[Route('/back/abonnement/delete/{id}', name: 'delete_abonnement')]
public function deleteAbonnement($id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $abonnementRepository = $entityManager->getRepository(Abonnement::class);
    $abonnemennt = $abonnementRepository->find($id);

    if (!$abonnemennt) {
        throw $this->createNotFoundException('Abonnement non trouvé avec l\'id '.$id);
    }

    $abonnementUtilisateurRepository = $entityManager->getRepository(AbonnementUtilisateur::class);
    $utilisateurs = $abonnementUtilisateurRepository->findBy(['abonnement' => $abonnemennt]);

    $entityManager->beginTransaction();
    try {
        foreach ($utilisateurs as $utilisateur) {
            $entityManager->remove($utilisateur);
        }

        $entityManager->remove($abonnemennt);
        $entityManager->flush();
        $entityManager->commit();

        $this->addFlash('success', 'abonnement a été supprimé avec succès.');
    } catch (\Exception $e) {
        $entityManager->rollback();
        $this->addFlash('error', 'Une erreur est survenue lors de la suppression de l\'utilisateur.');
    }

    return $this->redirectToRoute('back_abonnement');

}
#[Route('/back/UserAbonnement/add_abonnements', name: 'add_abonnements')]
public function addAbonnement(Request $request): Response
{
    $abonnemennt = new Abonnement();
    $form = $this->createForm(AbonnementType::class, $abonnemennt);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
     
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($abonnemennt);
        $entityManager->flush();

        return $this->redirectToRoute('back_abonnement');
    }

    return $this->render('back/UserAbonnement/add_abonnements.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('/abonnement/{id}/update', name: 'update_abonnement')]
public function updateAbonnement(Request $request, $id): Response{
    $entityManager = $this->getDoctrine()->getManager();
    $AbonnementRepository = $entityManager->getRepository(Abonnement::class);
    $abonnement = $AbonnementRepository->find($id);

    if (!$abonnement) {
        throw $this->createNotFoundException('Abonnement non trouvé avec l\'id '.$id);
    }

    $form = $this->createForm(AbonnementType::class, $abonnement);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
       
        $entityManager->flush();

        return $this->redirectToRoute('back_abonnement');
    }

    return $this->render('back/UserAbonnement/Abonnement_update.html.twig', [
        'abonnement' => $abonnement,
        'form' => $form->createView(),
    ]);
    

}

}
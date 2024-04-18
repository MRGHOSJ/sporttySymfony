<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Dompdf\Dompdf;


#[Route('/back/materiels')]
class MaterielController extends AbstractController
{
    #[Route('/', name: 'app_materiel_index', methods: ['GET'])]
    public function index(MaterielRepository $materielRepository, Request $request): Response
    {
        $sort = $request->query->get('sort', 'nom'); // Par défaut, tri par nom
        $materiels = $materielRepository->findAllSortedBy($sort);

        $searchTerm = $request->query->get('search');
        $materiels = $materielRepository->findBySearchTerm($searchTerm);


        return $this->render('back/materiel/allMateriel.html.twig', [
            'materiels' => $materiels,
        ]);
    }

    #[Route('/new', name: 'app_materiel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
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

                // Stockage du nom du fichier dans l'entité Materiel
                $materiel->setImage($nomFichier);
            }

            $entityManager->persist($materiel);
            $entityManager->flush();

            return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/materiel/addMateriel.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }



     // Ajoutez cette ligne pour importer la classe File

    #[Route('/{id}/edit', name: 'app_materiel_edit', methods: ['GET', 'POST'])]
    public function edit(Materiel $materiel, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaterielType::class, $materiel);
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

                // Stockage du nom du fichier dans l'entité Materiel
                $materiel->setImage($nomFichier);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/materiel/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }



    #[Route('/{id}/delete', name: 'app_materiel_delete', methods: ['POST'])]
    public function delete(Request $request, Materiel $materiel): Response
    {
        // Vérification des autorisations de suppression, par exemple :
        // $this->denyAccessUnlessGranted('DELETE', $materiel);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($materiel);
        $entityManager->flush();

        // Redirection vers la liste des matériaux après suppression
        return $this->redirectToRoute('app_materiel_index');
    }


    #[Route("/generate-pdf", name: "app_generate_pdf")]
    public function generatePdf(Request $request): Response
    {
        // Récupérer la liste des matériels depuis la base de données
        $materiels = $this->getDoctrine()->getRepository(Materiel::class)->findAll();

        // Créer une instance de Dompdf
        $dompdf = new Dompdf();

        // Construction du contenu HTML à partir du template twig
        $html = $this->renderView('back/materiel/pdf.html.twig', [
            'materiels' => $materiels,
        ]);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // (Optionnel) Paramètres de la mise en page
        $dompdf->setPaper('A4', 'portrait');

        // Rendu du PDF
        $dompdf->render();

        // Générer le nom du fichier PDF
        $pdfFileName = 'liste_materiels.pdf';

        // Créer une réponse avec le contenu du PDF
        $response = new Response($dompdf->output());

        // Ajouter les en-têtes pour indiquer au navigateur de télécharger le fichier
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $pdfFileName . '"');

        // Retourner la réponse
        return $response;
    }

}

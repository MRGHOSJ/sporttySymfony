<?php

namespace App\Controller;
use App\Entity\Abonnement;
use App\Entity\User;
use App\Entity\AbonnementUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use App\Form\UserAdminType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\LoginFormType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormError;
use App\Repository\EvenementsRepository;
class UserController extends AbstractController
{
   /**
     * @Route("/back/UserAbonnement/users", name="back_users")
     */
    #[Route('/back/UserAbonnement/users', name: 'back_users')]
    public function users(): Response
    {  
        $users = $this->getDoctrine()->getRepository(User::class)->createQueryBuilder('u')
        ->select('u.id','u.nom', 'u.prenom', 'u.email', 'u.password', 'u.role')
        ->getQuery()
        ->getResult();
        dump('ddd');
    return $this->render('back/UserAbonnement/users.html.twig', ['users' => $users]);

    }
    #[Route('/back/user/delete/{id}', name: 'delete_user')]
public function deleteUser($id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($id);

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé avec l\'id '.$id);
    }

    $abonnementUtilisateurRepository = $entityManager->getRepository(AbonnementUtilisateur::class);
    $abonnements = $abonnementUtilisateurRepository->findBy(['utilisateur' => $user]);

    $entityManager->beginTransaction();
    try {
        foreach ($abonnements as $abonnement) {
            $entityManager->remove($abonnement);
        }

        $entityManager->remove($user);
        $entityManager->flush();
        $entityManager->commit();

        $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');
    } catch (Exception $e) {
        $entityManager->rollback();
        $this->addFlash('error', 'Une erreur est survenue lors de la suppression de l\'utilisateur.');
    }

    return $this->redirectToRoute('back_users');

}
#[Route('/user/{id}/update', name: 'update_user')]
public function updateUser(Request $request, $id): Response{
    $entityManager = $this->getDoctrine()->getManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($id);

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé avec l\'id '.$id);
    }

    $form = $this->createForm(UserAdminType::class, $user);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $user->setPassword(hash('sha256', $user->getPassword())); 
        $entityManager->flush();

        return $this->redirectToRoute('back_users');
    }

    return $this->render('back/UserAbonnement/user_update.html.twig', [
        'user' => $user, 
    'form' => $form->createView(),
    ]);

}



#[Route('/back/UserAbonnement/add_user', name: 'add_user')]
public function addUser(Request $request, SluggerInterface $slugger): Response
{
    $user = new User();
    $form = $this->createForm(UserAdminType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le fichier téléchargé
$imageFile = $form->get('imageFile')->getData();

if ($imageFile) {
    // Générer un nom de fichier unique
    $newFilename = md5(uniqid()).'.'.$imageFile->guessExtension();

    // Déplacer le fichier vers le répertoire d'upload
    try {
        $imageFile->move(
            $this->getParameter('images_directory'),
            $newFilename
        );
    } catch (FileException $e) {
        // Gérer l'exception si le fichier ne peut pas être déplacé
    }

    // Mettre à jour l'entité User avec le chemin de l'image
    $user->setImageUser('/uploads/images/'.$newFilename);
}


        $user->setPassword(hash('sha256', $user->getPassword())); 

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('back_users');
    }

    return $this->render('back/UserAbonnement/user_add.html.twig', [
        'form' => $form->createView(),
    ]);
}


/*public function addUser(Request $request): Response
{
    $user = new User();
    $form = $this->createForm(UserAdminType::class, $user);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
       
        $user->setPassword(hash('sha256', $user->getPassword())); 
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('back_users');
    }

    return $this->render('back/UserAbonnement/user_add.html.twig', [
        'form' => $form->createView(),
    ]);
}
*/


//front
#[Route('/front/UserAbonnement/home/{id}', name: 'home_users')]
public function home(Security $security, $id, EvenementsRepository $evenementsRepository): Response
{
    // Récupérer l'utilisateur actuellement connecté
    $user = $security->getUser();
    $entityManager = $this->getDoctrine()->getManager();

    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($id);

// Récupérer tous les abonnements
$abonnementsRepository = $entityManager->getRepository(Abonnement::class);
$abonnements = $abonnementsRepository->findAll(); 

    return $this->render('front/index.html.twig', ['user' => $user, 'abonnements' => $abonnements , 
    
    'events' => $evenementsRepository->findNewEvents(),]);
}
#[Route('/front/UserAbonnement/Profile/{id}', name: 'profile_users')]
public function profile(Request $request, Security $security, $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($id);

    // Créer le formulaire en utilisant le UserType que vous avez créé
    $form = $this->createForm(UserType::class, $user);

    // Traiter le formulaire soumis
    $form->handleRequest($request);

    
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $user->setPassword(hash('sha256', $user->getPassword())); // Hacher le mot de passe avec SHA-256
        $entityManager->flush();

        // Rediriger l'utilisateur vers une autre page, par exemple son profil
        return $this->redirectToRoute('home_users', ['id' => $user->getId()]);
    }

    return $this->render('front/UserAbonnement/Profile.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
    ]);
}
#[Route('/register/{idAbonnement}', name: 'register_for_abonnement')]
public function registerForAbonnement(Request $request, $idAbonnement): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $userRepository = $entityManager->getRepository(User::class);
    $abonnementRepository = $entityManager->getRepository(Abonnement::class);

    // Récupérer l'utilisateur à partir de la session ou d'une autre méthode
    $user = $this->getUser();

    // Récupérer l'abonnement en fonction de son ID
    $abonnement = $abonnementRepository->find($idAbonnement);

    if (!$user || !$abonnement) {
        throw $this->createNotFoundException('Utilisateur ou abonnement non trouvé.');
    }

    // Ajouter l'abonnement à l'utilisateur
    $user->addAbonnement($abonnement);

    $entityManager->flush();

    return $this->redirectToRoute('back_users');
}
}
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
use App\Form\UserfrontType;
use App\Repository\UserRepository;

use App\Form\UserPasswordType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\LoginFormType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\UserAdminUpdateType;


class UserController extends AbstractController
{
   /**
     * @Route("/back/UserAbonnement/users", name="back_users")
     */
  
   #[Route('/back/UserAbonnement/users', name: 'back_users')]
   public function users(UserRepository $userRepository, Request $request): Response
   {
       $roleFilter = $request->query->get('role', 'ALL');
       $searchTerm = $request->query->get('search', '');
       $criteria = $request->query->get('criteria', 'nom'); // Par défaut, le critère est 'nom'
   
       $users = [];
   
       if (!empty($searchTerm)) {
           switch ($criteria) {
               case 'nom':
                   $users = $userRepository->findByUsernameStartingWith($searchTerm);
                   break;
               case 'prenom':
                   $users = $userRepository->findByPrenomStartingWith($searchTerm);
                   break;
               case 'email':
                   $users = $userRepository->findByEmailStartingWith($searchTerm);
                   break;
               // Ajoutez d'autres cas pour d'autres critères si nécessaire
               default:
                   $users = $userRepository->findByUsernameStartingWith($searchTerm);
           }
       } elseif ($roleFilter === 'ALL') {
           $users = $userRepository->findAll();
       } else {
           $users = $userRepository->findBy(['role' => $roleFilter]);
       }
   
       $usersWithSubscription = [];
       foreach ($users as $user) {
           $hasSubscription = $userRepository->hasSubscription($user->getId());
           $usersWithSubscription[$user->getId()] = $hasSubscription ? 'Completed' : 'Cancelled';
       }
   
       return $this->render('back/UserAbonnement/users.html.twig', [
           'users' => $users,
           'usersWithSubscription' => $usersWithSubscription,
           'selectedRole' => $roleFilter,
       ]);
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

    $form = $this->createForm(UserAdminUpdateType::class, $user);

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
public function home(Security $security, $id): Response
{
    // Récupérer l'utilisateur actuellement connecté
    $user = $security->getUser();
    $entityManager = $this->getDoctrine()->getManager();

    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($id);

// Récupérer tous les abonnements
$abonnementsRepository = $entityManager->getRepository(Abonnement::class);
$abonnements = $abonnementsRepository->findAll(); 
dump('abonnement reussi');
    return $this->render('front/index.html.twig', ['user' => $user, 'abonnements' => $abonnements]);
}
#[Route('/front/UserAbonnement/Profile/{id}', name: 'profile_users')]
public function profile(Request $request, Security $security, $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($id);


    $form = $this->createForm(UserfrontType::class, $user);

    $form->handleRequest($request);

    
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $user->setPassword(hash('sha256', $user->getPassword())); // Hacher le mot de passe avec SHA-256
        $entityManager->flush();

        // Rediriger l'utilisateur vers  son profil
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
    dump('dd');

    // Récupérer l'abonnement en fonction de son ID
    $abonnement = $abonnementRepository->find($idAbonnement);
dump('recupere abonnement');

    if (!$user || !$abonnement) {
        throw $this->createNotFoundException('Utilisateur ou abonnement non trouvé.');
    }

    // Créer une instance de AbonnementUtilisateur et l'associer à l'utilisateur et à l'abonnement
    $abonnementUtilisateur = new AbonnementUtilisateur();
    $abonnementUtilisateur->setUtilisateur($user);
    $abonnementUtilisateur->setAbonnement($abonnement);
    // Ajouter l'abonnementUtilisateur à l'utilisateur
    $user->addAbonnement($abonnementUtilisateur);
dump('assure persist');

    // Persist l'entité AbonnementUtilisateur
    $entityManager->persist($abonnementUtilisateur);

    // Mettre à jour l'entité User
    $entityManager->persist($user);
    $entityManager->flush();

    // Après avoir persisté l'entité AbonnementUtilisateur
    $this->addFlash('success', 'Votre inscription a été enregistrée avec succès.');

    // Rediriger vers la page d'accueil des utilisateurs avec l'ID de l'utilisateur
    return $this->redirectToRoute('home_users', ['id' => $user->getId()]);

}

#[Route('/edit_password/{id}', name: 'editPassword', methods: ['GET', 'POST'])]
public function editPassword(Request $request, Security $security, $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($id);

    $form = $this->createForm(UserPasswordType::class);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $oldPassword = $form->get('oldPassword')->getData();
        $oldPasswordHash = hash('sha256', $oldPassword);

        if ($oldPasswordHash !== $user->getPassword()) {
            $form->get('oldPassword')->addError(new FormError('Mot de passe incorrect.'));
            return $this->render('front/UserAbonnement/edit_password.html.twig', [
                'form' => $form->createView(),
              
            ]);
        }

        // Mettre à jour le nouveau mot de passe
        $newPassword = $form->get('newPassword')->getData();
        $user->setPassword(hash('sha256', $newPassword));
        $entityManager->flush();

        return $this->redirectToRoute('home_users', ['id' => $user->getId()]);
    }

    return $this->render('front/UserAbonnement/edit_password.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/mailer', name: 'mail')]
public function sendEmail(MailerInterface $mailer)
{
    $email = (new Email())
        ->from('serviceclientsporty@gmail.com')
        ->to('serviceclientsporty@gmail.com')
        ->subject('Test d\'envoi d\'e-mail avec Symfony')
        ->text('Ceci est un e-mail de test.');

    $mailer->send($email);

    return new Response('E-mail envoyé.');
}

}
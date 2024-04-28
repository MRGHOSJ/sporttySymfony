<?php

namespace App\Controller;
use App\Repository\UserRepository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
class EmailController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/validate-email', name: 'validate_email')]
    public function validateEmail(Request $request, MailerInterface $mailer): Response
    {
        $email = $request->request->get('email');
        if (empty($email)) {
           
            
            $this->addFlash('danger', 'the field must not be empty!');
            return $this->redirectToRoute('forgot');
        }
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            $this->addFlash('danger', 'Email not found, please enter a valid email address!');
            return $this->redirectToRoute('forgot');
        }    
          // Générer un code de confirmation
    $confirmationCode = substr(md5(uniqid(rand(), true)), 0, 6);

    // Enregistrer le code de confirmation dans la session
    $request->getSession()->set('confirmation_code', $confirmationCode);
    dump('confirm');
    // Enregistrer l'email dans la session pour la réinitialisation du mot de passe
    $request->getSession()->set('reset_email', $email);
    dump('reset');

    // Envoyer le code de confirmation par e-mail
    $this->sendConfirmationEmail($email, $confirmationCode, $mailer);
    dump('confirm12');
    
        // Supposons que vous vérifiez ici si l'e-mail est valide dans votre application
    
      /*  $message = (new Email())
            ->from('dinagharbi893@gmail.com')
            ->to($email)
            ->subject('Hello')
            ->text('Bonjour12');
    
        $mailer->send($message);
    
        return new Response('Message sent.');*/
        return $this->redirectToRoute('code', ['email' => $email, 'code' => $confirmationCode]);

        dump('code23');
    }
    
    private function sendConfirmationEmail(string $email, string $confirmationCode, MailerInterface $mailer): void
    {
        $email = (new Email())
            ->from('dinagharbi893@gmail.com')
            ->to($email)
            ->subject('Confirmation Code')
            ->text('Your confirmation code: ' . $confirmationCode);

        $mailer->send($email);
    }

   
    #[Route('/reset-password', name: 'reset_password')]
    public function resetPassword(Request $request): Response
    {
        $code = $request->request->get('code');
    
        $session = $request->getSession();
        $storedCode = $session->get('confirmation_code');
        dump('fff1');
        if ($code !== $storedCode) {
          
            $this->addFlash('danger', 'Invalid code, please try again!');
            dump('fff2');
            return $this->redirectToRoute('forgot');
            dump('fff3');
        }
        $email = $session->get('reset_email');
        dump('Email récupéré de la session : ' . $email);
        $response = $this->render('security/reset_password.html.twig', ['email' => $email]);
    
        // Inspectez la réponse
        dump($response->getContent());
        dump($response->getStatusCode());
        dump($response->headers->all());
    
        return $response;
    }
    
    #[Route('/new-password', name: 'new_password', methods: ['POST'])]
public function newPassword(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
{
    $email = $request->getSession()->get('reset_email');
    $user = $userRepository->findOneBy(['email' => $email]);

    if (!$user) {
        $this->addFlash('danger', 'User not found for email: ' . $email);
        return $this->redirectToRoute('forgot');
    }

    $newPassword = $request->request->get('newPassword');
    $confirmPassword = $request->request->get('confirmPassword');

    if ($newPassword !== $confirmPassword) {
        $this->addFlash('danger', 'Passwords do not match!');
        return $this->redirectToRoute('forgot');
    }

    $encodedPassword = hash('sha256', $newPassword);

    $user->setPassword($encodedPassword);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->flush();

    return $this->redirectToRoute('app_login');
}


    
    
}
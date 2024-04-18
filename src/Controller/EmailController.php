<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmailController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/validate-email', name: 'validate_email', methods: ['POST'])]
    public function validateEmail(Request $request, MailerInterface $mailer): Response
    {
        $email = $request->request->get('email');

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if ($user instanceof User) {
            // L'email est valide, générer un code de confirmation
            $confirmationCode = substr(md5(uniqid(rand(), true)), 0, 6);

            // Enregistrer le code de confirmation dans la session
            $request->getSession()->set('confirmation_code', $confirmationCode);
            // Enregistrer l'email dans la session pour la réinitialisation du mot de passe
            $request->getSession()->set('reset_email', $email);

            // Envoyer le code de confirmation par e-mail
            $this->sendConfirmationEmail($email, $confirmationCode, $mailer);

            return new Response('Confirmation code sent to the email address.');
        } else {
            return new Response('Invalid email address.', 400);
        }
    }

    private function sendConfirmationEmail(string $email, string $confirmationCode, MailerInterface $mailer): void
    {
        $email = (new Email())
            ->from('serviceclientsporty@gmail.com')
            ->to($email)
            ->subject('Confirmation Code')
            ->text('Your confirmation code: ' . $confirmationCode);

        $mailer->send($email);
    }

    #[Route('/confirm-code', name: 'confirm_code', methods: ['POST'])]
    public function confirmCode(Request $request): Response
    {
        $enteredCode = $request->request->get('confirmation-code');
        $storedCode = $request->getSession()->get('confirmation_code');

        if ($enteredCode === $storedCode) {
            // Code de confirmation correct, afficher une popup de réinitialisation du mot de passe
            return new Response('Correct confirmation code.');
        } else {
            // Code de confirmation incorrect, afficher une erreur
            return new Response('Incorrect confirmation code.', 400);
        }
    }

    #[Route('/reset-password', name: 'reset_password', methods: ['POST'])]
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Récupérer l'e-mail de réinitialisation depuis la session
        $email = $request->getSession()->get('reset_email');

        // Récupérer l'utilisateur correspondant à l'e-mail depuis la base de données
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        // Vérifier si l'utilisateur existe
        if ($user instanceof User) {
            // Récupérer le nouveau mot de passe depuis le formulaire
            $newPassword = $request->request->get('new-password');
            $confirmNewPassword = $request->request->get('confirm-new-password');

            // Vérifier si les mots de passe correspondent
            if ($newPassword !== $confirmNewPassword) {
                return new Response('Passwords do not match.', 400);
            }

            // Encoder le nouveau mot de passe avec bcrypt
            $hashedPassword = $passwordEncoder->encodePassword($user, $newPassword);

            // Mettre à jour le mot de passe de l'utilisateur
            $user->setPassword($hashedPassword);

            // Supprimer l'email de réinitialisation de la session
            $request->getSession()->remove('reset_email');

            // Enregistrer les modifications dans la base de données
            $this->entityManager->flush();

            // Retourner une réponse indiquant que le mot de passe a été réinitialisé avec succès
            return new Response('Password reset successfully.');
        } else {
            // L'utilisateur avec cet e-mail n'existe pas, retourner une erreur
            return new Response('User not found.', 404);
        }
    }
    // src/Controller/EmailController.php

#[Route('/show-validate-email-form', name: 'show_validate_email_form')]
public function showValidateEmailForm(): Response
{
    return $this->render('validate_email.html.twig');
}

}
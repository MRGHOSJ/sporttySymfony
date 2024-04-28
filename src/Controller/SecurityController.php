<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;




use Swift_Message;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class SecurityController extends AbstractController
{
   
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
     
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(AuthenticationUtils $authenticationUtils): RedirectResponse
    {   // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
     
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

    }
    #[Route(path: '/forgot', name: 'forgot')]
    public function forgotPassword(Request $request, UserRepository $userRepository,MailerInterface  $mailer, TokenGeneratorInterface  $tokenGenerator)
    {
        return $this->render("security/forgotPassword.html.twig");
    }
    #[Route(path: '/code', name: 'code')]
    public function code(Request $request, UserRepository $userRepository, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        $email = $request->getSession()->get('reset_email');
        $code = $request->getSession()->get('confirmation_code');
        return $this->render("security/code.html.twig", ['email' => $email, 'code' => $code]);
    }
    
   
}

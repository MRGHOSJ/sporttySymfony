<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
       // $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
    
        $form = $this->createForm(UserType::class, $user);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new user's password with SHA-256
            $hashedPassword = hash('sha256', $user->getPassword());
            $user->setPassword($hashedPassword);
    
            // Set their role
            $user->setRoles(['ADHERANT']);
    
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
    
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
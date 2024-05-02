<?php
namespace App\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Guard\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait; // Ajout de l'import
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCredentialsException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;


use ReCaptcha\ReCaptcha;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $urlGenerator;
    public const LOGIN_ROUTE = 'app_login';
    private $entityManager;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }


    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate('app_login');
    }


   
    
    public function getUser($credentials, UserProviderInterface $userProvider)
    {// Vérification du jeton CSRF
    $token = new CsrfToken('authenticate', $credentials['csrf_token']);
    if (!$this->csrfTokenManager->isTokenValid($token)) {
        throw new InvalidCsrfTokenException();
    }

  
    $recaptchaResponse = $credentials['g-recaptcha-response'];
    $recaptcha = new ReCaptcha($_ENV['RECAPTCHA_SECRET_KEY']);
    $response = $recaptcha->verify($recaptchaResponse);

    if (!$response->isSuccess()) {
        throw new CustomUserMessageAuthenticationException('Invalid captcha.');
    }

    if (!$response->isSuccess()) {
        throw new CustomUserMessageAuthenticationException('Invalid captcha.');
    }
    
        try {
            // Récupération de l'utilisateur en fonction de l'email
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
            if (!$user) {
                throw new UsernameNotFoundException('Email could not be found.');
            }
    
            // Récupérer le mot de passe haché stocké dans la base de données
            $hashedPassword = $user->getPassword();
    
            // Hacher le mot de passe fourni avec SHA-256
            $hashedInputPassword = hash('sha256', $credentials['password']);
    
            // Comparer les deux hachages pour vérifier si les mots de passe correspondent
            if ($hashedPassword !== $hashedInputPassword) {
                throw new AuthenticationException('Invalid credentials.');
            }
    
            return $user;
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException('Authentication failed: ' . $e->getMessage());
        }
    }
    

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Récupérer le mot de passe haché stocké dans la base de données
        $hashedPassword = $user->getPassword();
    
        // Hacher le mot de passe fourni avec SHA-256
        $hashedInputPassword = hash('sha256', $credentials['password']);
    
        // Comparer les deux hachages pour vérifier si les mots de passe correspondent
        if ($hashedPassword !== $hashedInputPassword) {
            throw new AuthenticationException('Invalid credentials.');
        }
    
        return true;
    }
    

    
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        $user = $token->getUser();

        if(in_array('ADMIN',$user->getRoles(),true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_back'));
        }
        if(in_array('ADHERANT', $user->getRoles(), true)) {
            // Assuming 'id' is a property of your User entity
            $userId = $user->getId();
            return new RedirectResponse($this->urlGenerator->generate('home_users', ['id' => $userId]));
        }
        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        //return new RedirectResponse($this->urlGenerator->generate('registration'));
    }
    public function supports(Request $request): bool
{
    return $request->attributes->get('_route') === self::LOGIN_ROUTE
        && $request->isMethod('POST');
}

public function getCredentials(Request $request): array
{
    return [
        'email' => $request->request->get('email'),
        'password' => $request->request->get('password'),
        'csrf_token' => $request->request->get('_csrf_token'),
        'g-recaptcha-response' => $request->request->get('g-recaptcha-response'),// Ajouter cette ligne
    
    ];
}

}

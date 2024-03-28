<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    #[Route('/back', name: 'app_back')]
    public function index(): Response
    {
        return $this->render('back/index.html.twig');
    }
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('back/pages/login.html.twig');
    }
    #[Route('/register', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('back/pages/register.html.twig');
    }
    #[Route('/back/basicElements', name: 'app_back_basicElements')]
    public function basicElements(): Response
    {
        return $this->render('back/pages/basicElements.html.twig');
    }
    #[Route('/back/basicTable', name: 'app_back_basicTable')]
    public function basicTable(): Response
    {
        return $this->render('back/pages/basicTable.html.twig');
    }
    #[Route('/back/buttons', name: 'app_back_buttons')]
    public function buttons(): Response
    {
        return $this->render('back/pages/buttons.html.twig');
    }
    #[Route('/back/chartjs', name: 'app_back_chartjs')]
    public function chartjs(): Response
    {
        return $this->render('back/pages/chartjs.html.twig');
    }
    #[Route('/back/dropdowns', name: 'app_back_dropdowns')]
    public function dropdowns(): Response
    {
        return $this->render('back/pages/dropdowns.html.twig');
    }
    #[Route('/error404', name: 'app_back_error404')]
    public function error404(): Response
    {
        return $this->render('back/pages/error404.html.twig');
    }
    #[Route('/back/mdi', name: 'app_back_mdi')]
    public function mdi(): Response
    {
        return $this->render('back/pages/mdi.html.twig');
    }
    #[Route('/back/typography', name: 'app_back_typography')]
    public function typography(): Response
    {
        return $this->render('back/pages/typography.html.twig');
    }
}

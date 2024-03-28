<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }
    #[Route('/about', name: 'app_front_about')]
    public function about(): Response
    {
        return $this->render('front/pages/about.html.twig');
    }
    #[Route('/blog', name: 'app_front_blog')]
    public function blog(): Response
    {
        return $this->render('front/pages/blog.html.twig');
    }
    #[Route('/contact', name: 'app_front_contact')]
    public function contact(): Response
    {
        return $this->render('front/pages/contact.html.twig');
    }
    #[Route('/elements', name: 'app_front_elements')]
    public function elements(): Response
    {
        return $this->render('front/pages/elements.html.twig');
    }
    #[Route('/gallery', name: 'app_front_gallery')]
    public function gallery(): Response
    {
        return $this->render('front/pages/gallery.html.twig');
    }
    #[Route('/pricing', name: 'app_front_pricing')]
    public function pricing(): Response
    {
        return $this->render('front/pages/pricing.html.twig');
    }
    #[Route('/singleBlog', name: 'app_front_singleBlog')]
    public function singleBlog(): Response
    {
        return $this->render('front/pages/singleBlog.html.twig');
    }
}

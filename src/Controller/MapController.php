<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\SaleDeSportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    #[Route('/map', name: 'app_map')]
    public function map(SaleDeSportRepository $SaleDeSportRepository): Response
    {
        $SaleDeSport = $SaleDeSportRepository->findAll();
        $markers = [];

        foreach ($SaleDeSport as $gym) {
            sscanf($gym->getLocation(), '%f, %f', $lat, $lng);  
            $markers[] = ['latitude' => $lat, 'longitude' => $lng];
        }
        
        return $this->render('front/map/index.html.twig', [
            'markers' => $markers,
        ]);
    }

}

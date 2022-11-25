<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use App\Repository\ReviewRepository;
use App\Service\ReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    #[Route('/city', name: 'app_city')]
    public function index(CityRepository $cityRepository): Response
    {
        return $this->render('city/index.html.twig', [
            'cities' => $cityRepository->findAllSortedBy('name','ASC')
        ]);
    }
    #[Route('/city/{id}', name: 'app_city_show')]
    public function show(City $city): Response
    {
        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $city->getRestaurants()
        ]);
    }    

    
}

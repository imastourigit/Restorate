<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Restaurant;
use App\Entity\Review;
use App\Form\RestaurantType;
use App\Form\ReviewType;
use App\Repository\RestaurantRepository;
use App\Repository\ReviewRepository;
use App\Service\RestaurantDelete;
use App\Service\ReviewService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController
{
    #[Route('/', name: 'app_restaurant_home')]
    public function home(RestaurantRepository $restaurantRepository): Response
    {
        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurantRepository->findAllSortedBy_10('createdAt','DESC')
            
        ]);
    }
    #[Route('/restaurant', name: 'app_restaurant')]
    public function index(RestaurantRepository $restaurantRepository): Response
    {
        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurantRepository->findAllSortedBy('createdAt','DESC')
            
        ]);
    }
    
    #[Route('/restaurant/new', name: 'app_restaurant_add', methods: ['GET','POST']),IsGranted('ROLE_RESTAURANT') ]
    public function add(RestaurantRepository $restaurantRepository,Request $request): Response
    {     
        $restaurant = new Restaurant();
        $media= new Media();
   
        $restaurant->addMedium($media)
        ->setUser($this->getUser());
      
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);
   
        if ($form->isSubmitted() && $form->isValid()) {
            $restaurant->setCreatedAt(new \DateTime());         
            $restaurantRepository->save($restaurant, true);
            $this->addFlash('success', 'Restaurant created!');
            return $this->redirectToRoute('app_restaurant_show', ['id' => $restaurant->getId()], Response::HTTP_SEE_OTHER);
             }
            return $this->renderForm('restaurant/new.html.twig', ['restaurant' => $restaurant,'form' => $form]);
    }


    #[Route('/restaurant/edit/{id}', name: 'app_restaurant_edit', methods: ['GET','POST']),IsGranted('ROLE_RESTAURANT')]
    public function edit(RestaurantRepository $restaurantRepository,Request $request,Restaurant $restaurant): Response
    {            
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($restaurant->getMedia() as $media_file) {
               $media_file->addRestaurant($restaurant);               
            }
            $restaurant->setUpdatedAt(new \DateTime());         
            $restaurantRepository->save($restaurant, true);
            $this->addFlash('success', 'Restaurant Updated!');
            return $this->redirectToRoute('app_restaurant_edit', ['id' => $restaurant->getId(),'restaurant' => $restaurant], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('restaurant/edit.html.twig', ['id' => $restaurant->getId(),'restaurant' => $restaurant,'form' => $form]);
    }


    #[Route('/restaurant/{id}', name: 'app_restaurant_show', methods: ['GET','POST'])]
    public function show(Restaurant $restaurant,Request $request,ReviewRepository $reviewRepository,ReviewService $reviewService): Response
    {   // Review Form inside the Restaurant details
        $review=new Review();
        $form_review = $this->createForm(ReviewType::class, $review);
        $form_review->handleRequest($request);
        if ($form_review->isSubmitted() && $form_review->isValid()) {
            //check if user is authenticated
            $this->denyAccessUnlessGranted('ROLE_USER');
            $review = $form_review->getData();
            
             
            $review->setRestaurant($restaurant)
                   ->setCreatedAt(new \DateTime())
                   ->setUser($this->getUser());
            // check if its first review on restaurant so we can create or update his comment       
            $review=$reviewService->get_user_review_on_restaurant($review,$this->getUser(),$reviewRepository);       
            $reviewRepository->save($review, true);

            $this->addFlash('success', 'Notation Added!');
            return $this->redirectToRoute('app_restaurant_show', ['id' => $restaurant->getId()]);
        }
            return $this->render('restaurant/show.html.twig', [
            'restaurant' => $restaurant,
            'form_review' => $form_review->createView()
            
        ]);
    }

    #[Route('restaurant/delete/{id}', name: 'app_restaurant_delete', methods: ['POST']),IsGranted('ROLE_RESTAURANT')]
    public function delete( Restaurant $restaurant,Request $request, RestaurantRepository $restaurantRepository,RestaurantDelete $restaurantDelete): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurant->getId(), $request->request->get('_token'))) 
        {   
           //Delete all restaurant children
            $restaurantDelete->deleteRestaurantChildren($restaurant,$this->getParameter('media_directory'));
            $this->addFlash('success', 'Restaurant deleted!');            
        }
        else
        {
            $this->addFlash('error', 'Error!');    
        }
        return $this->redirectToRoute('app_user_profile');
    }



}

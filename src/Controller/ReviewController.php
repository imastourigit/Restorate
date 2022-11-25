<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{

    #[Route('/reply/review/{id}', name: 'app_review_reply')]
    public function reply(Review $review,Request $request,ReviewRepository $reviewRepository): Response
    {
        $restaurant=$review->getRestaurant();
        $reply=new Review();
        $form=$this->createForm(ReviewType::class,$reply);
        $form->remove('note');
        
        $reply->setRestaurant($restaurant)
               ->setUser($this->getUser());
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
                $reply->setCreatedAt(new \DateTime())
                       ->setResponse($review);

                $reviewRepository->save($reply, true);
                $this->addFlash('success', 'You replied to the comment!');   
        return $this->redirectToRoute('app_restaurant_show', ['id' => $restaurant->getId()]);
        }

        else
        return $this->render('review/reply.html.twig', [
            'form' => $form->createView(),
            'review' => $review
        ]);
    }


    #[Route('restaurant/delete/review/{id}', name: 'app_review_delete')]
    public function delete(Review $review,Request $request,ReviewRepository $reviewRepository)
    {
        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->request->get('_token'))) 
        {
                // check if user is owner of comment
            if($review->getUser()->getId() == $this->getUser()->getId())
            {
            $restaurant=$review->getRestaurant();             
            $reviewRepository->remove($review, true);
            $this->addFlash('success', 'Review deleted!');
            return $this->redirectToRoute('app_restaurant_show', ['id' => $review->getRestaurant()->getId()]);
            }            

        }
            $this->addFlash('danger', 'Access denied!');            
            return $this->redirectToRoute('app_restaurant_show', ['id' => $review->getRestaurant()->getId()]);
           



    }
}

<?php
namespace App\Service;

use App\Entity\Restaurant;
use App\Entity\Review;
use App\Entity\User;
use App\Repository\ReviewRepository;

class ReviewService 
{

    public function swap_recent_review_values(Review $review1,Review $review2 ){

        $review1->setMessage($review2->getMessage())
                ->setCreatedAt(new \DateTime());
        if( null !==($review2->getNote()))                
        $review1->setNote($review2->getNote());
        return $review1;
    } 
   
    public function get_user_review_on_restaurant(Review $posted_review,User $user, ReviewRepository $reviewRepository){
            $review = new Review();
            $restaurant=$posted_review->getRestaurant();
        // check if its his first rating
        $reviews_list=$reviewRepository->findBy_User_Restaurant_Reviews($user->getId(),$restaurant->getId());
        $review_count=count($reviews_list);
            if($review_count > 0)
            {   
                // if the user has made a comment already, we update his comment
                $review=$reviews_list[0];
                $review=$this->swap_recent_review_values($review,$posted_review);

            }
            else
            {   // if its the user's first rating we keep the review details the same
                $review=$posted_review;
            }
    
        return $review;
    }
}

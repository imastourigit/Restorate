<?php

namespace App\Service;

use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;

class RestaurantDelete 
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;
    }
    public function deleteRestaurantChildren(Restaurant $restaurant,String $media_directory){

        foreach($restaurant->getMedia() as $media)
        {
        // File delete
        unlink($media_directory.'/'.$media->getFilename());
        // data delete
        $this->em->remove($media);
        }        
        foreach($restaurant->getReviews() as $review)
        {
        $this->em->remove($review);
        }    
        $this->em->remove($restaurant);
        $this->em->flush();        


    }
}

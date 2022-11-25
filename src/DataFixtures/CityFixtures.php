<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $array_cities=['Marrakech','Casablanca','Rabat','Tanger','Agadir','Laayoun'];
        foreach ($array_cities as $key => $value) 
        {
                $city= new City();
                $city->setName($value);
                $manager->persist($city);
        }

        $manager->flush();
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{   private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher=$hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $firstnames=['Adam','Farid','Ahmed','Mourad','Karim','Farid'];
        $lastnames=['jamal','jawad','Aziz','Mansour','Allal','Badr'];
        
        for ($i=0; $i < 12 ; $i++) { 
            $user = new User();
            $roles[] = 'ROLE_USER';
            
            if($i<4)
            $roles[] = 'ROLE_RESTAURANT';

            $password= $this->hasher->hashPassword($user,"123456");
            $user->setFirstname($firstnames[random_int(0,5)])
            ->setLastname($lastnames[random_int(0,5)])
            ->setUsername("username_".$i)
            ->setPassword($password)
            ->setEmail("username_".$i."@gmail.com")
            ->setRoles($roles)            
            ->setCreatedAt(new \DateTime());


            unset($roles);            
            $manager->persist($user);
              
        }        

        $manager->flush();
    }
}

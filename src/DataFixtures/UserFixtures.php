<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1 ; $i <= 10; $i++){
            $user = new User();
            $user->setUsername("utilisateur$i")
                 ->setEmail("email$i@centrale-marseille.fr")
                 ->setPassword("$i$i$i")
                 ->setFirstName("$i fn")
                 ->setLastName("$i ln")
                 ->setPromo($i);
            $manager->persist($user);
        }

        $manager->flush();
    }
}

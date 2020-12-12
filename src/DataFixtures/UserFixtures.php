<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Ad;
use App\Entity\Comment;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");
        $user_list = [] ;
        for ($i = 1 ; $i <= 100; $i++){
            $user = new User();
            $nom = $faker->userName;
            $user->setUsername($nom)
                 ->setEmail($nom."@".$faker->freeEmailDomain)
                 ->setPassword("test")
                 ->setFirstName($faker->firstName)
                 ->setRoles($faker->creditCardDetails) /* TODO : changer en mode ["Role_User"]*/
                 ->setLastName($faker->lastName)
                 ->setPromo($faker->numberBetween($min = 2010, $max = 2023));
            $user_list[$i] = $user;
            $manager->persist($user);
        }
        for ($j = 1 ; $j <= mt_rand(150,250);$j++){
            $ad = new Ad();
            $dateAd = $faker->dateTimeBetween('-6 months');
            $ad->setAuthor($user_list[mt_rand(1,100)])
                ->setTitle($faker->sentence($nbWords = 3, $variableNbWords = true))
                ->setResolved($faker->boolean)
                ->setDescription($faker->text($maxNbChars = 200))
                ->setLocation($faker->city)
                ->setDate($dateAd)
                ->setType($faker->numberBetween($min = 0, $max = 5));
            $manager -> persist($ad);

            for ($l = 1; $l <= mt_rand(4,9); $l++){
                $comment = new Comment();
                $days = (new \DateTime())->diff($ad->getDate())->days;
                $comment->setAuthor($user_list[$l % 100])
                    ->setAd($ad)
                    ->setDate($faker->dateTimeBetween('-' . $days . 'days'))
                    ->setContent($faker->text($maxNbChars = 200));
                $manager->persist($comment);
            }
        }
        

        $manager->flush();
    }
}

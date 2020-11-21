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

        for ($i = 1 ; $i <= 10; $i++){
            $user = new User();
            $user->setUsername($faker->name)
                 ->setEmail($faker->email)
                 ->setPassword($faker->word)
                 ->setFirstName($faker->firstName)
                 ->setRoles($faker->creditCardDetails)
                 ->setLastName($faker->lastName)
                 ->setPromo($faker->numberBetween($min = 2010, $max = 2023));
            $manager->persist($user);

            for ($j = 1 ; $j <= mt_rand(3,5);$j++){
                $ad = new Ad();
                $dateAd = $faker->dateTimeBetween('-6 months');
                $ad->setAuthor($user)
                    ->setTitle($faker->sentence($nbWords = 3, $variableNbWords = true))
                    ->setResolved($faker->boolean)
                    ->setDescription($faker->text($maxNbChars = 200))
                    ->setLocation($faker->city)
                    ->setDate($dateAd)
                    ->setType($faker->numberBetween($min = 0, $max = 5));
                $manager -> persist($ad);

                for ($l = 1; $l <= mt_rand(6,10); $l++){
                    $comment = new Comment();
                    $days = (new \DateTime())->diff($ad->getDate())->days;
                    $comment->setAuthor($user)
                        ->setAd($ad)
                        ->setDate($faker->dateTimeBetween('-' . $days . 'days'))
                        ->setContent($faker->text($maxNbChars = 200));
                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}

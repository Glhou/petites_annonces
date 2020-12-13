<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Ad;
use App\Entity\Comment;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $encoder = $this->encoder;
        $faker = \Faker\Factory::create("fr_FR");
        $user_list = [] ;
        for ($i = 1 ; $i <= 100; $i++){
            $user = new User();
            $nom = $faker->userName;

            $user->setUsername($nom)
                 ->setEmail($nom."@".$faker->freeEmailDomain)
                 ->setPassword("test")
                 ->setFirstName($faker->firstName)
                 ->setRoles(["ROLE_USER"])
                 ->setLastName($faker->lastName)
                 ->setPromo($faker->numberBetween($min = 2010, $max = 2023));
            $user_list[$i] = $user;
        }
        for ($i = 1 ; $i <= 100; $i++){
            $hash = $encoder->encodePassword($user_list[$i], $user_list[$i]->getPassword());
            $user_list[$i]->setPassword($hash);
            $manager->persist($user_list[$i]);
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
            $userAdmin = new User();
            $userSuperAdmin = new User();
            $userAdmin->setUsername("admin")
                ->setEmail($nom."@".$faker->freeEmailDomain)
                ->setPassword("test")
                ->setFirstName($faker->firstName)
                ->setRoles(["ROLE_ADMIN"])
                ->setLastName($faker->lastName)
                ->setPromo($faker->numberBetween($min = 2010, $max = 2023));
            $userSuperAdmin->setUsername("superadmin")
                ->setEmail($nom."@".$faker->freeEmailDomain)
                ->setPassword("test")
                ->setFirstName($faker->firstName)
                ->setRoles(["ROLE_ADMIN"])
                ->setLastName($faker->lastName)
                ->setPromo($faker->numberBetween($min = 2010, $max = 2023));
            $userAdmin->setPassword($encoder->encodePassword($userAdmin,$userAdmin->getPassword()));
            $userSuperAdmin->setPassword($encoder->encodePassword($userSuperAdmin,$userSuperAdmin->getPassword()));
            $manager->persist($userAdmin);
            $manager->persist($userSuperAdmin);

        }
        

        $manager->flush();
    }
}

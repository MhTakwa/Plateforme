<?php

namespace App\DataFixtures;

use App\Entity\Cours;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       $cours=new Cours();
       $cours->setLibelle(' Atelier Conception et Développement');
       $manager->persist($cours);
        $manager->flush();
    }
}

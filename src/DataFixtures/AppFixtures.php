<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       $cour=new Cours();
       $cours->setLibelle('Conception');
       $manager->persist($cours);
        $manager->flush();
    }
}

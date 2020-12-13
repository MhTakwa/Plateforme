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
       $cours->setLibelle('Conception');
       $manager->persist($cours);
        $manager->flush();
    }
}

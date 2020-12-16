<?php

namespace App\DataFixtures;

use App\Entity\Cours;
use App\Entity\Tuteur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder=$passwordEncoder;
     }
    public function load(ObjectManager $manager)
    {
        
        $tuteur=new Tuteur();
        $tuteur->setNom('Ben salah');
        $tuteur->setPrenom('Nesrine');
        $tuteur->setEmail("nesrine@nesrine.com");
        $tuteur->setPassword($this->passwordEncoder->EncodePassword($tuteur,'nesrine'));
       $cours=new Cours();
       $cours->setLibelle(' Atelier Conception et Développement');
       $cours->setTuteur($tuteur);
       $manager->persist($cours);
        $manager->flush();
    }
}

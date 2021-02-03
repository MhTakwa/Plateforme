<?php

namespace App\DataFixtures;

use App\Entity\Cours;
use App\Entity\Tuteur;
use App\Entity\User;
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
        $tuteur->setNom('mhadhbi');
        $tuteur->setPrenom('takwa');
        $tuteur->setEmail("takwa@takwa.com");
        $tuteur->setGrade("Professeur");
        $tuteur->setTel(55111222);
        $tuteur->setSpecialite("Developpement Web");
        $tuteur->setPassword($this->passwordEncoder->EncodePassword($tuteur,'nesrine'));
        $tuteur->setStatus(1);
        $manager->persist($tuteur);
       $cours=new Cours();
       $cours->setLibelle(' Atelier Conception et Développement');
       $cours->setTuteur($tuteur);
       $manager->persist($cours);
       $user=new User();
       $user->setEmail("mahdi@mahdi.com");
       $user->setNom('Mhadhbi');
       $user->setPrenom('Mahdi');
       $user->setPassword($this->passwordEncoder->EncodePassword($tuteur,'mahdi'));
       $user->setRoles(['ROLE_ADMIN']);
       $user->setStatus(1);
       $manager->persist($user);
       
        $manager->flush();
    }
}

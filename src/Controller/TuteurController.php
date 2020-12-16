<?php

namespace App\Controller;
use App\Entity\Cours;
use App\Entity\Tuteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
class TuteurController extends AbstractController
{
    /**
     * @Route("/my", name="dashboard")
     */
    public function dashboard(UserInterface $user): Response
    {
       
        return $this->render('tuteur/dashboard.html.twig', [
           'courses'=>$user->getCours(),
           'tuteur'=>$user
        ]);
    }
}

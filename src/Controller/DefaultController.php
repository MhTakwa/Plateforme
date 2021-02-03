<?php

namespace App\Controller;
use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default")
     */
    public function index(Request $request)
    {
       
        return $this->render('base.html.twig');
    }
      /**
     * @Route("/courses", name="cours", methods={"GET"})
     */
    public function courses(CoursRepository $coursRepository): Response
    {
        $cours=$coursRepository->findBy(['status'=>1]);
        return $this->render('home/cours.html.twig', [
            'cours' => $cours,
        ]);
    }

     

}

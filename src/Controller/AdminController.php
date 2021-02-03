<?php

namespace App\Controller;
use App\Entity\Apprenant;
use App\Entity\Groupe;
use App\Form\ApprenantType;
use App\Repository\TuteurRepository;
use App\Repository\GroupeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{

     /**
     * @Route("/admin", name="admin")
     */
    public function admin(Request $request,TuteurRepository $tuteurRepository,GroupeRepository $groupeRepository)
    {
        $count_tuteurs=$tuteurRepository->findBy(['status'=>2]);
        $count_groupes=$groupeRepository->findBy(['status'=>2]);

        return $this->render('admin/index.html.twig',[
            'count_tuteurs'=>$count_tuteurs,
            'count_groupes'=>$count_groupes
        ]);
    }

}

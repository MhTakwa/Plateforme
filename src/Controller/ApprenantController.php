<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Apprenant;
use App\Form\ApprenantType;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/")
 */
class ApprenantController extends AbstractController
{
    /**
     * @Route("/apprenants", name="apprenant_index", methods={"GET"})
     */
    public function index(Request $request,ApprenantRepository $apprenantRepository,GroupeRepository $groupeRepository): Response
    {
        //$apprenants=$apprenantRepository->findByGroup();
        $count_all=$apprenantRepository->findAll();
        $count_approve=$apprenantRepository->findBy(['status'=>1]);
        $count_attente=$apprenantRepository->findBy(['status'=>2]);
        $count_refuse=$apprenantRepository->findBy(['status'=>0]);
        /////tous les groupe
        $groupes =$groupeRepository->findAll();
        
        $filter=(int)$request->get('filter'); 
        if($filter)
        $apprenants=$apprenantRepository->findBy(['status'=>$filter]);
        
        return $this->render('apprenant/index.html.twig', [
            'apprenants' => $apprenants,
            'count_all'=>$count_all,
            'count_approve'=>$count_approve,
            'count_attente'=>$count_attente,
            'count_refuse'=>$count_refuse,
            'groupes'=>$groupes
        ]);
    }
       /**
     * @Route("/apprenant/my", name="dashboard_apprenant")
     */
    public function dashboard_apprenant(UserInterface $user): Response
    {  
       
        return $this->render('apprenant/dashboard.html.twig', [
           'courses'=>$user->getCoursInscris(),
           'apprenant'=>$user
        ]);
    }
    /**
     * @Route("/groupes_apprenants", name="groupes_apprenants_index", methods={"GET"})
     */
    public function groupes_apprenants(Request $request,ApprenantRepository $apprenantRepository,GroupeRepository $groupeRepository): Response
    {
        
        $count_all=$groupeRepository->findAll();
        $count_approve=$groupeRepository->findBy(['status'=>1]);
        $count_attente=$groupeRepository->findBy(['status'=>2]);
        $count_refuse=$groupeRepository->findBy(['status'=>0]);
        /////tous les groupe
        $groupes =$groupeRepository->findAll();

        $filter=(int)$request->get('filter'); 
        if($filter || $request->get('filter')!=null)  
        $groupes=$groupeRepository->findBy(['status'=>$filter]); 
        
        return $this->render('apprenant/index.html.twig', [
          
            'count_all'=>$count_all,
            'count_approve'=>$count_approve,
            'count_attente'=>$count_attente,
            'count_refuse'=>$count_refuse,
            'groupes'=>$groupes
        ]);
    }

    /**
     * @Route("/new", name="apprenant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $apprenant = new Apprenant();
        $form = $this->createForm(ApprenantType::class, $apprenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($apprenant);
            $entityManager->flush();

            return $this->redirectToRoute('apprenant_index');
        }

        return $this->render('apprenant/new.html.twig', [
            'apprenant' => $apprenant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="apprenant_show", methods={"GET"})
     */
    public function show(Apprenant $apprenant): Response
    {
        return $this->render('apprenant/show.html.twig', [
            'apprenant' => $apprenant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="apprenant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Apprenant $apprenant): Response
    {
        $form = $this->createForm(ApprenantType::class, $apprenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('apprenant_index');
        }

        return $this->render('apprenant/edit.html.twig', [
            'apprenant' => $apprenant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="apprenant_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Apprenant $apprenant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$apprenant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($apprenant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('apprenant_index');
    }
}

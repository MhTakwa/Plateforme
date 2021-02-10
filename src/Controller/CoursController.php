<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Form\CoursInitType;
use App\Repository\CoursRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/cours")
 */
class CoursController extends AbstractController
{ 
  
      /**
     * @Route("/", name="cours_index", methods={"GET"})
     */
    public function index(CoursRepository $coursRepository): Response
    {
        $cours=$coursRepository->findBy(['status'=>1]);
        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
        ]);
    }
       /**
     * @Route("/inscrire/{id}", name="cours_Inscrire", methods={"GET","POST"})
     */
    public function cours_inscrire(Cours $cours,Request $request,TokenStorageInterface $token): Response
    {//dd($token->getToken()->getUser());
        $this->denyAccessUnlessGranted('ROLE_APPRENANT');//dd($cours->getGroupe()->contains($token->getToken()->getUser()->getGroupe()));
        if(!$cours->getGroupe()->contains($token->getToken()->getUser()->getGroupe())){
          return  $this->redirectToRoute('non-destinée');
        }
        
        $apprenant=$token->getToken()->getUser();
        $testCours=new Cours();
        $form = $this->createForm(CoursInitType::class, $testCours);
        $form->handleRequest($request);
         
        if ($form->isSubmitted() && $form->isValid()) { //dd(strcmp($testCours->getCle(),$cours->getCle()));
            if(strcmp($testCours->getCle(),$cours->getCle())==0){
                $apprenant->addCoursInscri($cours); 
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
    
                return $this->redirectToRoute('cours_show',['id'=>$cours->getId()]);
            }
            $form->get('cle')->addError(new FormError('Clé invalide !'));

          
        }

        return $this->render('cours/inscription_cours.html.twig', [
            'cours' => $testCours,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="cours_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cour = new Cours();
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cour->setStatus(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cour);
            $entityManager->flush();

            return $this->redirectToRoute('cours_index');
        }

        return $this->render('cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/cours_init/{id}", name="cours_init", methods={"GET","POST"})
     */
    public function initialiser(Request $request,Cours $cours): Response
    {
        $form = $this->createForm(CoursInitType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cours->setStatus(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('cours_show',['id'=>$cours->getId()]);
        }

        return $this->render('cours/init_cours.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="cours_show", methods={"GET"})
     */
    public function show(Cours $cour): Response
    {
       return $this->render('cours/show.html.twig', [
            'cour' => $cour,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cours_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cours $cour): Response
    {
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cours_index');
        }

        return $this->render('cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cours_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cours $cour): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cour->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cours_index');
    }
}

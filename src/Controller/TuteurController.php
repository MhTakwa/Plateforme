<?php

namespace App\Controller;

use App\Entity\Tuteur;
use App\Form\TuteurType;
use App\Repository\TuteurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

    /**
     * @Route("/tuteurs")
     */
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
    /**
     * @Route("/", name="tuteur_index", methods={"GET"})
     */
    public function index(Request $request,TuteurRepository $tuteurRepository): Response
    {
        $tuteurs=$tuteurRepository->findAll();
        $count_all=$tuteurRepository->findAll();
        $count_approve=$tuteurRepository->findBy(['status'=>1]);
        $count_attente=$tuteurRepository->findBy(['status'=>2]);
        $count_refuse=$tuteurRepository->findBy(['status'=>0]);
        
        $filter=(int)$request->get('filter'); 
        if($filter)
        $tuteurs=$tuteurRepository->findBy(['status'=>$filter]);
        
        return $this->render('tuteur/index.html.twig', [
            'tuteurs' => $tuteurs,
            'count_all'=>$count_all,
            'count_approve'=>$count_approve,
            'count_attente'=>$count_attente,
            'count_refuse'=>$count_refuse
        ]);
    }
         /**
     * @Route("/{id}/valider", name="tuteur_valider", methods={"GET","POST"})
     */
    public function valider(Request $request, Tuteur $tuteur): Response
    {
        $tuteur->setStatus(1);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('tuteur_index');
    }
      /**
     * @Route("/{id}/refuser", name="tuteur_refuser", methods={"GET","POST"})
     */
    public function refuse(Request $request, Tuteur $tuteur): Response
    {
        $tuteur->setStatus(0);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('tuteur_index');
    }
    /**
     * @Route("/new", name="tuteur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tuteur = new Tuteur();
        $form = $this->createForm(TuteurType::class, $tuteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tuteur);
            $entityManager->flush();

            return $this->redirectToRoute('tuteur_index');
        }

        return $this->render('tuteur/new.html.twig', [
            'tuteur' => $tuteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tuteur_show", methods={"GET"})
     */
    public function show(Tuteur $tuteur): Response
    {
        return $this->render('tuteur/show.html.twig', [
            'tuteur' => $tuteur,
        ]);
    }
 
    /**
     * @Route("/{id}/edit", name="tuteur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tuteur $tuteur,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TuteurType::class, $tuteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
           
            if ($image) {
               $filename=$this->uploadImage($image,$slugger);
               if($filename) 
                   $tuteur->setImage($filename);
              
           }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('tuteur/edit.html.twig', [
            'tuteur' => $tuteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tuteur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tuteur $tuteur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tuteur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tuteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tuteur_index');
    }
    private function uploadImage($image, SluggerInterface $slugger){ 
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
       // die(var_dump($newFilename));
        // Move the file to the directory where brochures are stored
        try {
            $image->move(
                $this->getParameter('profile_images_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
         return $newFilename;
      

    }



}

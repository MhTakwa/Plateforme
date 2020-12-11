<?php

namespace App\Controller;

use App\Entity\Ressource;
use App\Entity\Section;
use App\Form\RessourceType;
use App\Repository\RessourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Route("/ressource")
 */
class RessourceController extends AbstractController
{
    /**
     * @Route("/", name="ressource_index", methods={"GET"})
     */
    public function index(RessourceRepository $ressourceRepository): Response
    {
        return $this->render('ressource/index.html.twig', [
            'ressources' => $ressourceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{section}/new", name="ressource_new", methods={"GET","POST"})
     */
    public function new(Request $request,Section $section,SluggerInterface $slugger): Response
    {
        $ressource = new Ressource();
        $ressource->setSection($section); 
        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document = $form->get('Document')->getData();
            if ($document) {
                $filename=$this->uploadFile($document,$slugger,$ressource);
                if($filename) 
                    $ressource->setDocument($filename);
             //  dd($document->getContent());
            }
            $entityManager = $this->getDoctrine()->getManager();
            $ressource->setDateCreation(new \DateTime('now'));
             $entityManager->persist($ressource);
            $entityManager->flush();
                           
            

            return  $this->redirectToRoute('cours_show',['id'=>$ressource->getSection()->getCours()->getId()]);
        }

        return $this->render('ressource/new.html.twig', [
            'ressource' => $ressource,
            'form' => $form->createView(),
          
        ]);
    }

    /**
     * @Route("/{id}", name="ressource_show", methods={"GET"})
     */
    public function show(Ressource $ressource): Response
    {
        return $this->render('ressource/show.html.twig', [
            'ressource' => $ressource,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ressource_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ressource $ressource): Response
    { 
       if($ressource->getContenu())
              $ressource->setDocument(new File($ressource->getDocument()));

        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $document = $form->get('Document')->getData();
            if ($document) {
                $filename=$this->uploadFile($document,$slugger,$ressource);
                if($filename) 
                    $ressource->setDocument($filename);
             //  dd($document->getContent());
            }
            $this->getDoctrine()->getManager()->flush();

            return  $this->redirectToRoute('cours_show',['id'=>$ressource->getSection()->getCours()->getId()]);
        }

        return $this->render('ressource/edit.html.twig', [
            'ressource' => $ressource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ressource_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ressource $ressource): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ressource->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ressource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ressource_index');
    }
    private function uploadFile($file, SluggerInterface $slugger,Ressource $ressource){ 
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
       // die(var_dump($newFilename));
        // Move the file to the directory where brochures are stored
        try {
            $file->move($this->getParameter('ressources_directory').'/'. $ressource->getSection()->getId() ,$newFilename);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
         return $newFilename;
      

    }
}

<?php

namespace App\Controller;
 
use App\Entity\Section;
use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use App\Repository\SoumissionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/activite")
 */
class ActiviteController extends AbstractController
{
    /**
     * @Route("/", name="activite_index", methods={"GET"})
     */
    public function index(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

       /**
     * @Route("/qcm/{id}", name="qcm", methods={"GET"})
     */
    public function qcm(Activite $activite,ActiviteRepository $activiteRepository)
    {
       // $activite->setDocument($this->getParameter('ressources_directory').'/'. $activite->getSection()->getId().'/'.$activite->getDocument());
        return $this->render('activite/qcm.html.twig',["activite"=>$activite]);
    }
    /**
     * @Route("/{section}/new", name="activite_new", methods={"GET","POST"})
     */
    public function new(Request $request,Section $section,SluggerInterface $slugger): Response
    {
        $activite = new Activite();
        $activite->setSection($section);
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            $activite->setFinSoumission(new \DateTime($request->get('activite[finSoumission]')));
            $activite->setDateCreation(new \DateTime('now')); 
            $activite->setPhase(0);//// avant soumission
            $entityManager = $this->getDoctrine()->getManager();
            $document = $form->get('Document')->getData();
            if ($document) {
                $filename=$this->uploadFile($document,$slugger,$activite);
                if($filename) 
                    $activite->setDocument($filename);
             //  dd($document->getContent());
            }
            $entityManager->persist($activite);
            $entityManager->flush();

            return  $this->redirectToRoute('cours_show',['id'=>$activite->getSection()->getCours()->getId()]);
        }

        return $this->render('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }
      /**
     * @Route("/activite/eval/{id}", name="activiteEval", methods={"GET"})
     */
    public function phaseEval(SoumissionRepository $soumissionRepository,ActiviteRepository $activiteRepository,Activite $activite): Response
    {
        $entityManager=$this->getDoctrine()->getManager();
        $activite->setPhase(1);
        $entityManager->flush();
        $soumissions=$soumissionRepository->findAll();
        return $this->render('soumission/index.html.twig', [
            'soumissions' =>$soumissions,
            'activite'=>$activite
        ]);
    }

    /**
     * @Route("/{id}", name="activite_show", methods={"GET"})
     */
    public function show(Activite $activite): Response
    {  return $this->render('activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="activite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Activite $activite): Response
    {
        if($activite->getContenu())
              $activite->setDocument(new File($ressource->getDocument()));
        else
            $activite->setDocument(new File());
        
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document = $form->get('Document')->getData();
            if ($document) {
                $filename=$this->uploadFile($document,$slugger,$activite);
                if($filename) 
                    $activite->setDocument($filename);
             //  dd($document->getContent());
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activite_index');
        }

        return $this->render('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="activite_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Activite $activite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activite_index');
    }
    private function uploadFile($file, SluggerInterface $slugger,Activite $activite){ 
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
       // die(var_dump($newFilename));
        // Move the file to the directory where brochures are stored
        try {
            $file->move($this->getParameter('ressources_directory').'/'. $activite->getSection()->getId() ,$newFilename);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
         return $newFilename;
      

    }
}

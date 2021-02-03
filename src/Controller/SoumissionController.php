<?php

namespace App\Controller;
use App\Entity\Apprenant;
use App\Entity\Activite;
use App\Entity\Soumission;
use App\Form\SoumissionType;
use App\Repository\SoumissionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SoumissionController extends AbstractController
{
    /**
     * @Route("/soumissions/{id}", name="soumission_index", methods={"GET"})
     */
    public function index(SoumissionRepository $soumissionRepository,Activite $activite): Response
    {
        $soumissions=$soumissionRepository->findBy(['activite'=>$activite]);
        return $this->render('soumission/index.html.twig', [
            'soumissions' =>$soumissions,
            'activite'=>$activite
        ]);
    }
      

    /**
     * @Route("/soumissions/{id}/new", name="soumission_new", methods={"GET","POST"})
     */
    public function new(Request $request,Activite $activite,UserInterface $apprenant,SluggerInterface $slugger): Response
    {
        $soumission = new Soumission();
        $soumission->setActivite($activite);
        $soumission->setApprenant($apprenant);
        $form = $this->createForm(SoumissionType::class, $soumission);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $soumission->setModification(new \DateTime('now'));
            $document = $form->get('document')->getData();
            if ($document) {
                $filename=$this->uploadFile($document,$slugger,$soumission);
                if($filename) 
                    $soumission->setDocument($filename);
             //  dd($document->getContent());
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($soumission);
            $entityManager->flush();

            return $this->redirectToRoute('soumission_show',['id'=>$soumission->getId()]);
        }

        return $this->render('soumission/new.html.twig', [
            'soumission' => $soumission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("soumissions/show/{id}", name="soumission_show", methods={"GET"})
     */
    public function show(Soumission $soumission): Response
    {
        return $this->render('soumission/show.html.twig', [
            'soumission' => $soumission,
        ]);
    }

    /**
     * @Route("soumissions/{id}/edit", name="soumission_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Soumission $soumission,UserInterface $apprenant,SluggerInterface $slugger): Response
    {
        $soumission->setDocument($this->getParameter('soumissions_directory').'/'. $soumission->getActivite()->getId().'/'.$soumission->getDocument());
        $form = $this->createForm(SoumissionType::class, $soumission);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $soumission->setModification(new \DateTime('now'));
            $document = $form->get('document')->getData();
            if ($document) {
                $filename=$this->uploadFile($document,$slugger,$soumission);
                if($filename) 
                    $soumission->setDocument($filename);
       
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('soumission_show',['id'=>$soumission->getId()]);
        }

        return $this->render('soumission/edit.html.twig', [
            'soumission' => $soumission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="soumission_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Soumission $soumission): Response
    {
        if ($this->isCsrfTokenValid('delete'.$soumission->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($soumission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('soumission_index');
    }
    private function uploadFile($file, SluggerInterface $slugger,Soumission $soumission){ 
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
       // die(var_dump($newFilename));
        // Move the file to the directory where brochures are stored
        try {
            $file->move($this->getParameter('soumissions_directory').'/'. $soumission->getActivite()->getId() ,$newFilename);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
         return $newFilename;
      

    }
}

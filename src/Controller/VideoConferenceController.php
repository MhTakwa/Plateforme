<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Message;
use App\Entity\VideoConference;
use App\Form\VideoConferenceType;
use App\Repository\VideoConferenceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/videoConference")
 */
class VideoConferenceController extends AbstractController
{
    /**
     * @Route("/joinMeeting/{id}", name="join_meeting", methods={"GET","POST"})
     */
    public function join(Request $request,VideoConference $videoConference): Response
    {
        return $this->render("video_conference/join.html.twig",['videoConference'=>$videoConference]);
    }
    /**
     * @Route("/", name="video_conference_index", methods={"GET"})
     */
    public function index(VideoConferenceRepository $videoConferenceRepository): Response
    {
        return $this->render('video_conference/index.html.twig', [
            'video_conferences' => $videoConferenceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{cours}/new", name="video_conference_new", methods={"GET","POST"})
     */
    public function new(Request $request,Cours $cours,UserInterface $tuteur): Response
    {
        $videoConference = new VideoConference();
        $videoConference->setCours($cours);
        $form = $this->createForm(VideoConferenceType::class, $videoConference);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) { 
            $entityManager = $this->getDoctrine()->getManager();
            $videoConference->setDate(new \DateTime($request->get('video_conference[date]')));
            $entityManager->persist($videoConference);
            $entityManager->flush();
            $message=new Message();
            $message->setSender($tuteur);
            foreach($cours->getApprenants() as $apprenant){
                $message->addReceiver($apprenant);
            }
            $message->setObject("Visio conférence pour le cours ".$cours->getLibelle());
            $message->setBody('lien de réunion :<a  href={{path("join_meeting",{"id":'.$videoConference->getId().'})}} target="_blank">Joindre la reunion</a>');
          
           $entityManager->persist($message);
           $entityManager->flush(); 
           

            return $this->redirectToRoute('cours_show',['id'=>$videoConference->getCours()->getId()]);
        }

        return $this->render('video_conference/new.html.twig', [
            'video_conference' => $videoConference,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="video_conference_show", methods={"GET"})
     */
    public function show(VideoConference $videoConference): Response
    {
        return $this->render('video_conference/show.html.twig', [
            'video_conference' => $videoConference,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="video_conference_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, VideoConference $videoConference): Response
    {
        $form = $this->createForm(VideoConferenceType::class, $videoConference);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $videoConference->setDate(new \DateTime($request->get('video_conference[date]')));
            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('cours_show',['id'=>$videoConference->getCours()->getId()]);
        }

        return $this->render('video_conference/edit.html.twig', [
            'video_conference' => $videoConference,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="video_conference_delete", methods={"DELETE"})
     */
    public function delete(Request $request, VideoConference $videoConference): Response
    {
        if ($this->isCsrfTokenValid('delete'.$videoConference->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $cours=$videoConference->getCours();
            $entityManager->remove($videoConference);
            $entityManager->flush();
            
        }

        return $this->redirectToRoute('cours_show',['id'=>$cours->getId()]);
    }
    
}

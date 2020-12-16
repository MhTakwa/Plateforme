<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\InscriptionType;
use App\Entity\Tuteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('ressource_index');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/inscription", name="app_inscription")
     */
    public function Inscription(Request $request, EntityManagerInterface $entityManager,UserPasswordEncoderInterface $encoder)
    {
        $user = new Tuteur();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);// dd($form);
        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword($encoder->EncodePassword($user,$user->getPassword()));
            $user->setRoles(['ROLE_TUTEUR']);
            $entityManager->persist($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/inscription.html.twig', [
            'form' => $form->createView()
        ]);
     }
}

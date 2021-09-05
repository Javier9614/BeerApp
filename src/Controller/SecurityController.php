<?php

namespace App\Controller;

use App\Form\RegisterFormType;
use App\Manager\BeerManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('beer_list');
         }

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
     * @Route("/register", name="app_register")
     */

    
     

    public function register(Request $req, EntityManagerInterface $em, UserPasswordEncoderInterface $encode){

        
        $form = $this->createForm(RegisterFormType::class);
        
        $form->handleRequest($req);
        
        if($form->isSubmitted() && $form->isValid()){

            $profileImage=$form->get('imageFile')->getData();
            
            $user = $form->getData(); 
            $password = $user->getPassword();
            $hash = $encode->encodePassword($user, $password);
            $user->setPassword($hash);
            $user->setRoles(["ROLE_USER"]);

            $timestamp=gettimeofday();
            $idImage=($timestamp["sec"]);
            
            $em->persist($user);
            $em->flush();
            if($profileImage){
            $path = $this->getParameter("kernel.project_dir")."/public/images/users";
            $filename ="$idImage".'.'.$profileImage->guessClientExtension();

            $profileImage->move(
                $path ,
                $filename 
            );
        }
        if($profileImage){

            $user->setImage("/images/users/$filename");
        }
 


            
            $em->flush(); 

            return $this->redirectToRoute("app_login");
            // return $guard->authenticateUserAndHandleSuccess(
            //     $user, $req, $formAuthenticator, 'beer_list');
        }

        return $this->render(

            "./security/register.html.twig", 

            [
                "formRegister" => $form->createView()
            ]
        );

    }
}

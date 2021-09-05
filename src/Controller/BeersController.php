<?php

namespace App\Controller;

use App\Entity\Beers;
use App\Manager\BeerManager;
use App\Form\BeerFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class BeersController extends AbstractController {

    
    /**
     * @Route("/beers", name="beer_list")
     */
    
    public function listBeer(EntityManagerInterface $em){
        
        $repository = $em->getRepository(Beers::class);
        
        $beers = $repository->findAll();
        
        return $this->render(
            "./beers/list.html.twig",
            [
                "beers" => $beers
                ]
            );
            
            
        }
        /**
         * @Route("/beers/beer/{id}", name="one_beer")
         */
    
         public function oneBeer($id,EntityManagerInterface $em){
    
            $repo = $em->getRepository(Beers::class);
            $beer = $repo->find($id);
    
    
            return $this->render('./beers/beer.html.twig', ["beer" => $beer]);
         }

    /**
     * @Route("/beers/add", name="new_beer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addBeer(Request $req, EntityManagerInterface $em, BeerManager $manager){

        $form = $this->createForm(BeerFormType::class);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $beerImage=$form->get('imageFile')->getData();

             $beer = $form->getData();
            $beerName=str_replace(' ', '',$beer->getName() );
            $timestamp=gettimeofday();
            $idImage=($timestamp["sec"]);
            
            $em->persist($beer);
            $em->flush();
            if($beerImage){
            $path = $this->getParameter("kernel.project_dir")."/public/images/beers";
            $filename ="$beerName$idImage".'.'.$beerImage->guessClientExtension();
            
            $beerImage->move(
                    $path ,
                    $filename 
                );
            }  
            if($beerImage){ 
                $beer->setImage("/images/beers/$filename");
            } 
                $manager->updateImage($this->getParameter("kernel.project_dir")."/public/images/Aprovado.png", $path."/$filename");
                
            $em->flush(); 

            return $this->redirectToRoute("beer_list");
        }

        return $this->render(

            "./beers/new.html.twig", 

            [
                "form" => $form->createView()
            ]
        );
    }

    
    /**
     * @Route("/beers/edit/{id}", name="edit_beer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editBeer(Beers $beer, Request $req, EntityManagerInterface $em,BeerManager $manager){

        $form = $this->createForm(BeerFormType::class, $beer);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){

            $beerImage=$form->get('imageFile')->getData();

            $beer = $form->getData();
            $beerName=str_replace(' ', '',$beer->getName() );
            $timestamp=gettimeofday();
            $idImage=($timestamp["sec"]);
            $em->persist($beer);
            $em->flush();
            if($beerImage){
            $path = $this->getParameter("kernel.project_dir")."/public/images/beers";
            $filename ="$beerName$idImage".'.'.$beerImage->guessClientExtension();
            
            $beerImage->move(
                $path ,
                $filename 
            );
        }
 

            $beer->setImage("/images/beers/$filename");

            $manager->updateImage($this->getParameter("kernel.project_dir")."/public/images/Aprovado.png", $path."/$filename");
            
            $em->flush(); 

            return $this->redirectToRoute("beer_list");
        }

        return $this->render(

            "./beers/edit.html.twig", 

            [
                "form" => $form->createView()
            ]
        );

    }
    /**
     * @Route("/beers/delete/{id}", name="del_beer")
     * @IsGranted("ROLE_ADMIN")
     */

     public function deleteBeer(Beers $beer, Request $req, EntityManagerInterface $em){
        $form = $this->createForm(BeerFormType::class, $beer);
        $form->handleRequest($req);

        $em->remove($beer);
        $em->flush();

        return $this->redirectToRoute("beer_list");
     }
}
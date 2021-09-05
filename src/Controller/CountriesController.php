<?php

namespace App\Controller;

use App\Entity\Countries;
use App\Form\CountryFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CountriesController extends AbstractController{

    /**
     * @Route("/countries", name="list_countries")
     */
    public function listCountries(EntityManagerInterface $em){

        $repository = $em->getRepository(Countries::class);

        $countries = $repository->findAll();

        return $this->render(
            "./countries/list.html.twig",
            [
                "countries" => $countries,
            ]
        );

    }

    /**
     * @Route("/countries/add", name="country_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addCountry(Request $req ,EntityManagerInterface $em){

       $form = $this->createForm(CountryFormType::class);

       $form->handleRequest($req);

       if($form->isSubmitted() && $form->isValid()){
           
           
        $country = $form->getData();
        $em->persist($country);
        $em->flush();

        return $this->redirectToRoute("list_countries");
       }


        return $this->render(
            "./countries/new.html.twig",
            [
                "form" => $form->createView()
            ]
        );

    }

    /**
     * @Route("/countries/edit", name="country_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editCountry(Countries $country,Request $req,EntityManagerInterface $em){

        $form = $this->createForm(CountryFormType::class, $country);

       $form->handleRequest($req);

       if($form->isSubmitted() && $form->isValid()){
           
           
        $country = $form->getData();
        $em->persist($country);
        $em->flush();

        return $this->redirectToRoute("list_countries");
       }


        return $this->render(
            "./countries/edit.html.twig",
            [
                "form" => $form->createView()
            ]
        );

    }
}
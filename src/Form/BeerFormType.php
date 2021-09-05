<?php

namespace App\Form;

use App\Entity\Beers;
use App\Entity\Countries;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BeerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('graduation')
            ->add('imageFile', FileType::class, ['mapped'=>false, "required"=>false])
            ->add('style')
            ->add('fermentationType'
            , ChoiceType::class,
                [
                    "choices" => [
                        "Baja Fermentacion" => "Baja fermentacion",
                        "Alta Fermentacion" => "Alta fermentacion",
                        "Fermentacion Espontanea" => "Fermentacion espontanea"
                    ]
                ]
                    )
            ->add('introduced')
            ->add('description')
            ->add('country', EntityType::class,
                [
                    'class' => Countries::class,
                    'choice_label' => "name"
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Beers::class,
        ]);
    }
}

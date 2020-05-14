<?php

namespace App\Form;

use App\Entity\Modele;
use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class RechercheVoitureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('modele', EntityType::class, [
                'class' => Modele::class,
                'choice_label' => function ($modele) {
                    return $modele->getMarque()->getNom().' '.$modele->getNom();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('modele')
                              ->orderBy('modele.nom', 'ASC');
                },
                'group_by' => function($modele) {                
                    return $modele->getMarque()->getNom();
                },
            ])
            /*->add('typeCarrosserie', EntityType::class, [
                'class' => Voiture::class,
                'choice_value' => function ($voiture) {
                    return $voiture->getTypeCarrosserie();
                },
            ])*/
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

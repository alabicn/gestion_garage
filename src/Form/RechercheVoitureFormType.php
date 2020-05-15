<?php

namespace App\Form;

use App\Entity\Modele;
use App\Entity\Voiture;
use App\Entity\Garage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
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
                'multiple' => true
            ])
            ->add('garage', EntityType::class, [
                'class' => Garage::class,
                'choice_label' => function ($garage) {
                    return $garage->getNom();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('garage')
                              ->andWhere('garage.estFerme = :bool')
                              ->setParameter('bool', false);
                },
                'expanded' => true,
                'multiple' => true
            ])
            ->add('typeCarrosserie', ChoiceType::class, [
                'choices' => [
                    'Berlin' => 'Berlin',
                    'Monospace' => 'Monospace',
                    'Cabriolet' => 'Cabriolet',
                    'Coupé' => 'Coupé',
                ],
                'expanded' => true,
                'multiple' => true
            ])
            ->add('nbPortes', ChoiceType::class, [
                'choices' => [
                    '3' => 3,
                    '5' => 5,
                    '7' => 7
                ],
                'expanded' => true,
                'multiple' => true
            ])
            
            ->add('reinitialiser', ResetType::class, [
                'attr' => ['class' => 'btn btn-danger']
            ])
            ->add('valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Voiture;
use App\Entity\Modele;
use App\Entity\Garage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class VoitureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('modele', EntityType::class, [
                'class' => Modele::class,
                'choice_label' => function ($modele) {
                    return $modele->getMarque()->getNom()." ".$modele->getNom();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('modele')
                              ->leftJoin('modele.marque', 'marque')
                              ->orderBy('marque.nom', 'ASC');
                },
                'group_by' => function ($modele) {
                    return $modele->getMarque()->getNom();
                },
                'placeholder' => 'Choisissez un modèle',
                'required' => true
            ])
            ->add('garage', EntityType::class, [
                'class' => Garage::class,
                'choice_label' => function ($garage) {
                    return $garage->getNom();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('garage')
                              ->andWhere('garage.estFerme = :bool')
                              ->setParameter('bool', false)
                              ->orderBy('garage.nom', 'ASC');
                },
                'placeholder' => 'Choisissez un garage',
            ])
            ->add('immatriculation', TextType::class, [
                'help' => 'Le format est AA-001-AA',
            ])
            ->add('dateFabrication', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('kilometrage', IntegerType::class)
            ->add('typeCarrosserie', TextType::class)
            ->add('carburant', TextType::class)
            ->add('nbPortes', IntegerType::class)
            ->add('boiteDeVitesse', TextType::class)
            ->add('prix', MoneyType::class)
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}

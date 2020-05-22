<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Voiture;
use App\Entity\Garage;
use App\Entity\Option;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityManagerInterface;


class RechercheVoitureFormType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'choice_label' => function ($marque) {
                    return $marque->getNom();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('marque')
                              ->orderBy('marque.nom', 'ASC');
                },
                'required' => false,
                'multiple' => true
            ])
            /*->add('modele', TextType::class, [
                'required' => false
            ])*/
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
                'expanded' => true,
                'multiple' => true
            ])
            ->add('typeCarrosserie', ChoiceType::class, [
                'choices' => $this->getTypesCarrosserie(),
                'expanded' => true,
                'multiple' => true
            ])
            ->add('carburant', ChoiceType::class, [
                'choices' => $this->getCarburants(),
                'expanded' => true,
                'multiple' => true
            ])
            ->add('boites', ChoiceType::class, [
                'choices' => $this->getBoites(),
                'expanded' => true,
                'multiple' => true
            ])
            ->add('nbPortes', ChoiceType::class, [
                'choices' => $this->getNbPortes(),
                'expanded' => true,
                'multiple' => true
            ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => function($option) {
                    return $option->getTitle();
                },
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('option')
                              ->orderBy('option.title', 'ASC');
                },
                'expanded' => true,
                'multiple' => true
            ])
            ->add('prix', RangeType::class, [
                'attr' => [
                    'min' => round($this->getMinMaxPrix()['min_prix'] * 1.2, 2),
                    'max' => round($this->getMinMaxPrix()['max_prix'] * 1.2, 2),
                    'class' => 'custom-range'
                ],
                'disabled' => true
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

    
    function getTypesCarrosserie() {
        
        $typesCarrosserie = $this->em->getRepository(Voiture::class)->findAllTypesCarrosserie();
        
        return $typesCarrosserie;
    }
    
    function getCarburants() {

        $carburants = $this->em->getRepository(Voiture::class)->findAllCarburants();

        return $carburants;
    }

    function getBoites() {

        $boites = $this->em->getRepository(Voiture::class)->findAllBoites();

        return $boites;
    }

    function getNbPortes() {

        $nbPortes = $this->em->getRepository(Voiture::class)->findAllNbPortes();

        return $nbPortes;
    }

    function getMinMaxPrix() {

        $arr_prix = $this->em->getRepository(Voiture::class)->findMinMaxPrixDeVoiture();

        return $arr_prix;
    }
}

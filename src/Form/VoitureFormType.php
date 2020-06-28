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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
                'help' => 'Le numéro d\'immatriculation doit être au format AA-001-AA',
            ])
            ->add('dateFabrication', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'required' => false
            ])
            ->add('kilometrage', IntegerType::class, [
                'attr' => [
                    'min' => 1
                ],
                'required' => false
            ])
            ->add('typeCarrosserie', TextType::class, [
                'required' => false
            ])
            ->add('carburant', TextType::class, [
                'required' => false
            ])
            ->add('nbPortes', ChoiceType::class, [
                'choices' => [
                    '3' => 3,
                    '5' => 5
                ], 
                'placeholder' => 'Choisissez un nombre',
                'required' => false
            ])
            ->add('boiteDeVitesse', TextType::class, [
                'required' => false
            ])
            ->add('voitureOptions', CollectionType::class, [
                'entry_type'   => VoitureOptionFormType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'label'        => false,
                'by_reference' => false,
                'required'     => false,
            ])
            ->add('prix', MoneyType::class, [
                'required' => false
            ])
            ->add('photoPrincipal', FileType::class, [
                'label' => 'Photo principal de la voiture (JPG/PNG, max. 1Mo)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Veuillez respecter les restrictions de taille et de format',
                    ])
                ],
            ])
            ->add('photos', FileType::class, [
                'label' => 'Des photos divers de la voiture (JPG/PNG, max. 1Mo)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details

                'multiple' => true,
                'required' => false,
                'data_class' => null,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes

            ])
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

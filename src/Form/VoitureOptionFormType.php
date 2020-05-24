<?php

namespace App\Form;

use App\Entity\VoitureOption;
use App\Entity\Option;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Doctrine\ORM\EntityRepository;

class VoitureOptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('option', EntityType::class, [
                'class' => Option::class,
                'choice_label' => function ($option) {
                    return $option->getTitle();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('option')
                              ->orderBy('option.title', 'ASC');
                }
            ])
            ->add('nombre', IntegerType::class, [
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('remove', ButtonType::class, [
                'attr' => [
                    'class' => 'js-remove btn btn-danger btn-sm'
                ],
                'label' => 'Supprimer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VoitureOption::class,
            'attr' => ['class' => 'form-horizontal vehicleOption-item container-sm']
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Garage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Service\ServiceInformations;

class GarageFormType extends AbstractType
{
    private $serviceInformations;

    public function __construct(ServiceInformations $serviceInformations)
    {
        $this->serviceInformations = $serviceInformations;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('numeroTelephone', TelType::class, [
                'required' => false
            ])
            ->add('adresse', TextType::class)
            ->add('ville', ChoiceType::class, [
                'choices' => $this->sortVilles(),
                'mapped' => false,
                'required' => true
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Garage::class,
        ]);
    }

    function sortVilles() {
        foreach ($this->serviceInformations->getAllVilles() as $codePostal=>$ville) {
            $arr_villes[$ville] = $ville."(".$codePostal.")";
        }
        
        return $arr_villes;
    }
}

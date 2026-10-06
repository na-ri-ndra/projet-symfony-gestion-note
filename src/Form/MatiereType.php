<?php

namespace App\Form;

use App\Entity\Matiere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'attr' => [
                'class' => 'form-control mb-3', // classe Bootstrap
                'placeholder' => 'Nom de le matiere'],
                'label' => 'Nom matiere'
            ])
            ->add('coefficient', NumberType::class,[
                'attr' => [
                'class' => 'form-control mb-3', // classe Bootstrap
                'placeholder' => 'Coeficient'
            ],
            'label' => 'Coeficient'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matiere::class,
        ]);
    }
}

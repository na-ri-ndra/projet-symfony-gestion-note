<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       $builder
        ->add('nom', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3', // classe Bootstrap
                'placeholder' => 'Nom de l\'étudiant'
            ],
            'label' => 'Nom'
        ])
        ->add('prenom', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'placeholder' => 'Prénom de l\'étudiant'
            ],
            'label' => 'Prénom'
        ])
        ->add('dateNaissence', DateType::class, [
            'widget' => 'single_text',
            'attr' => [
                'class' => 'form-control mb-3',
                'placeholder' => 'Date de naissance'
            ],
            'label' => 'Date de naissance'
        ]);
    }
}

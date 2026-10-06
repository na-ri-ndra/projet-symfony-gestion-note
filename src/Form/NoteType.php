<?php
// src/Form/NoteType.php
namespace App\Form;

use App\Entity\Note;
use App\Entity\Etudiant;
use App\Entity\Matiere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note', NumberType::class,[
                'attr' => [
                'class' => 'form-control mb-3', // classe Bootstrap
                'placeholder' => 'Note'
            ],
            'label' => 'Note'
            ])
            ->add('etudiant', EntityType::class, [
                'class' => Etudiant::class,
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'form-control mb-3', // classe Bootstrap
                    'placeholder' => 'Nom de l\'étudiant'],
                'label' => 'Nom etudiant'
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'form-control mb-3', // classe Bootstrap
                    'placeholder' => 'Nom de l\'étudiant'
                ],
                'label' => 'Nom Matiere'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}

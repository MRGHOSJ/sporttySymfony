<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Abonnement;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Type', TextType::class, [
            'label' => 'Type',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Entrez votre type',
            ],
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 2]),
            ],
        ])
        ->add('prix', NumberType::class, [
            'label' => 'Price',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Enter the price',
            ],
            'scale' => 2, // Nombre de décimales à afficher
            'constraints' => [
                new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
              
            ],
        ])
        ->add('description', TextType::class, [
            'label' => 'Description',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Entrez votre description',
            ],
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 2]),
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'btn btn-primary',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}

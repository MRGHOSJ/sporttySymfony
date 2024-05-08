<?php

namespace App\Form;

use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Positive;




class Programme1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Name of course',
            'attr' => ['placeholder' => 'Saisissez le nom de programme'],
            'constraints' => [
                new NotBlank(['message' => 'Please enter a name.']),
            
                new Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage' => 'L\'adresse doit contenir au moins {{ limit }} caractères',
                    'maxMessage' => 'L\'adresse ne doit pas dépasser {{ limit }} caractères',
                ]),
            ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Saisissez une description'],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a description.']),
                
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'L\'adresse doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'L\'adresse ne doit pas dépasser {{ limit }} caractères',
                    ]),
                ],
                ])

                ->add('duree', IntegerType::class, [
                    'label' => 'Durée',
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'constraints' => [
                        new Type(['type' => 'integer', 'message' => 'La durée doit être un nombre entier.']),
                        new Positive(['message' => 'Duration must be a positive number.'])
                    ]
                ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}

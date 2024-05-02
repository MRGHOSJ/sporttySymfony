<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Programme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Positive;


class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Name of course',
            'attr' => [
                'class' => 'form-control'
            ],
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
           
            
            ->add('coach' ,TextType::class,[
                'label' => 'Name of coach',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    
                    new  Assert\NotBlank(['message' => 'Please enter a name of the coach']),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'L\'adresse doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'L\'adresse ne doit pas dépasser {{ limit }} caractères',
                    ]),
                ],
                ])
            ->add('jours', ChoiceType::class, [
                'label' => 'Jours',
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Monday' => 'Monday',
                    'Tuesday' => 'Tuesday',
                    'Wednesday' => 'Wednesday',
                    'Thursday' => 'Thursday',
                    'Friday' => 'Friday',
                    'Saturday' => 'Saturday',
                    'Sunday' => 'Sunday',
                ],
                'placeholder' => 'Choose an option', // Optionnel : affiche un texte vide par défaut
            'label' => 'jours', // Optionnel : pour ne pas répéter le label
            'constraints' => [
                new NotBlank(['message' => 'Please select a Day']),
                new Choice([
                    'choices' => [
                      
                             'Monday',
                            'Tuesday',
                             'Wednesday',
                            'Thursday',
                           'Friday',
                             'Saturday',
                            'Sunday',
                        
                        ],
                        
                        ])  ,], ])
                 
            ->add('duree', IntegerType::class, [
                    'label' => 'Durée',
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'constraints' => [
                        new Type(['type' => 'integer', 'message' => 'La durée doit être un nombre entier.']),
                        new Positive(['message' => 'Duration must be a positive number.'])
                    ]
                ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'In groups' => 'In groups',
                    'individuel' => 'individuel',
                ],
                'placeholder' => 'Choose an option', // Optionnel : affiche un texte vide par défaut
                'constraints' => [
                    new NotBlank(['message' => 'Please select a type']), // Correction : changer le message pour le type
                    new Choice([
                        'choices' => [
                            'In groups', // Correction : changer les choix pour les types
                            'individuel',
                        ]
                    ])
                ],
            ])
          
            ->add('prix',IntegerType::class, [
                'label' => 'Price',
                'attr' => [
                    'class' => 'form-control'
                ],                'constraints' => [
                    new NotBlank(['message' => 'Please enter a price ']),
                    new Type(['type' => 'integer', 'message' => 'Le prix doit être un nombre']),
                ],
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Image',

                'constraints' => [
                    new NotBlank([
                        'message' => 'Please upload an image file.',
                    ]),
                    
                ],
                'invalid_message' => 'Le prix doit être un nombre à virgule flottante (float).', // Message personnalisé
                ])
            ->add('lienvideo', TextType::class, [
                'label' => 'Lien vidéo',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a valid video link.']),
                    new Url(['message' => 'Please enter a valid video link.']),
                ],
            ])
            ->add('idProgramme', EntityType::class, [
                'class' => Programme::class,
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Programme',
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-user btn-block mt-4'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}

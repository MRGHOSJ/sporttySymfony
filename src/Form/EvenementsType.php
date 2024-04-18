<?php

namespace App\Form;

use App\Entity\Evenements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class EvenementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add( 'nomEvent', TextType::class, [
             'label' => 'nomEvent',
            'attr' => ['placeholder' => 'Enter title'],
            'constraints' => [
                new NotBlank(['message' => 'Please enter a Title.']) ,
                new Length([
                    'min' => 3,
                    'max' => 100,
                    'minMessage' => 'The event name must be at least {{ limit }} characters long.',
                    'maxMessage' => 'The event name cannot be longer than {{ limit }} characters.',
                ]),],
                ])

            ->add('descriEvent', TextType::class, [
                'label' => 'descriEvent',
               'attr' => ['placeholder' => 'Enter  description'],
               'constraints' => [
                   new NotBlank(['message' => 'Please enter a Description.']) ,
                   new Length([
                    'min' => 10,
                    'max' => 255,
                    'minMessage' => 'The description must be at least {{ limit }} characters long.',
                    'maxMessage' => 'The description cannot be longer than {{ limit }} characters.',
                ]),
            
                ],
                   ])

            ->add('categorieEvent', ChoiceType::class, [
                'choices' => [
                    'Group training' => 'Group training',
                    'Fitness classes' => 'Fitness classes',
                    'Yoga sessions' => 'Yoga sessions',
                    'Special events' => 'Special events',
                    'Dance classes' => 'Dance classes',
                    'Sports clinics' => 'Sports clinics',
                    'Family activities' => 'Family activities',
                    'Cycling' => 'Cycling',
                    'challenges and competitions' => 'challenges and competitions',

                ],
                'placeholder' => 'Choose an option', // Optionnel : affiche un texte vide par défaut
                'label' => 'Category', // Optionnel : pour ne pas répéter le label
                'constraints' => [
                    new NotBlank(['message' => 'Please select a category.']),
                    new Choice([
                        'choices' => [
                            'Group training',
                            'Fitness classes',
                            'Yoga sessions',
                            'Special events',
                            'Dance classes',
                            'Sports clinics',
                            'Family activities',
                            'Cycling',
                            'challenges and competitions',
                        ],
                        
                     ])  ,], ])
            
            ->add('dateEvent', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'html5' => true, // Active le widget de date HTML5
                'data' => new \DateTime(), // Initialise avec la date système
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today', // Restriction sur les dates antérieures
                        'message' => 'The event date must be today or in the future.',
                    ]),
                ], ])
                ->add('heureEvent', TimeType::class, [
                    'label' => 'Time',
                    'widget' => 'single_text',
                    'html5' => true,
                    'constraints' => [
                        new NotBlank(['message' => 'Please enter the time.']), // Le champ est obligatoire
                    ],
                ])

            ->add('lieuEvent', TextType::class, [
                'label' => 'lieuEvent',
               'attr' => ['placeholder' => 'Enter  a place'],
               'constraints' => [
                   new NotBlank(['message' => 'Please enter the place.']) ,
                   new Length([
                    'min' => 3,
                    'max' => 100,
                    'minMessage' => 'The location must be at least {{ limit }} characters long.',
                    'maxMessage' => 'The location cannot be longer than {{ limit }} characters.',
                ]),
                new Regex([
                    'pattern' => '/^[a-zA-Z\s]+$/',
                    'message' => 'The location must only contain letters and spaces.',
                ]),
                ],
                   ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenements::class,
        ]);
    }
}

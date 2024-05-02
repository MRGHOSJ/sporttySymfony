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
            'attr' => ['class' => 'form-control form-control-user'],
            'label' => 'Name',
        ])
            
           
            
            ->add('coach' ,TextType::class,[
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Coach',
            ])
            ->add('jours', ChoiceType::class, [
                'label' => 'Days',
                'attr' => ['class' => 'form-control form-control-user'],
                
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
            'label' => ' Days', // Optionnel : pour ne pas répéter le label
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
                'attr' => ['class' => 'form-control form-control-user'],
                    'label' => 'Duration',
                  
              ])
                  
            ->add('type', ChoiceType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Type',
                
                'choices' => [
                    'Par groupe' => 'groupe',
                    'Individuelle' => 'individuelle',
                ],
                'placeholder' => 'Choose an option', // Optionnel : affiche un texte vide par défaut
                'constraints' => [
                    new NotBlank(['message' => 'Please select a type']), // Correction : changer le message pour le type
                    new Choice([
                        'choices' => [
                            'groupe', // Correction : changer les choix pour les types
                            'individuelle',
                        ]
                    ])
                ],
            ])
          
            ->add('prix',IntegerType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Price',
               
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
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Link video',
               
            ])
            ->add('idProgramme', EntityType::class, [
                'class' => Programme::class,
                'attr' => ['class' => 'form-control form-control-user'],
                'choice_label' => 'nom',
               
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

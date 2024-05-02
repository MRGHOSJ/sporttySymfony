<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ReclamationFrontType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',ChoiceType::class,[
                'choices' => [
                    'Payment Issue' => 'Payment Issue',
                'Equipment Problem' => 'Equipment Problem',
                'Discomfort in Facilities'=>'Discomfort in Facilities',
                'Security Issue'=> 'Security Issue',
                'Reservation Issue'=>  'Reservation Issue',
                'Capacity Issue in Class'   => 'Capacity Issue in Class',
                'Improvement Suggestions'        => 'Improvement Suggestions',
                'Others' =>  'Others'] ,
                'placeholder' => 'Choose an option', 
                'constraints' => [
                    new NotBlank(['message' => 'Please select a category.']),
                    new Choice([
                        'choices' => ['Payment Issue', 'Equipment Problem', 'Discomfort in Facilities', 'Security Issue', 'Reservation Issue', 'Capacity Issue in Class', 'Improvement Suggestions', 'Others'],
                        'message' => 'Please select a valid category.'
                    ])
                ]
                
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Description cannot be empty.']),
                    new Length([
                        'min' => 10,
                        'max' => 1000,
                        'minMessage' => 'Description must be at least {{ limit }} characters long.',
                        'maxMessage' => 'Description cannot be longer than {{ limit }} characters.'
                    ]) 
                ]
                ])
                ->add('nbrEtoile', TextType::class, [
                    'label' => 'Rate Us',
                    'constraints' => [
                        new NotBlank(['message' => 'Please select a rating.']),
                    ],
                ]);
                
                
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}

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
                ->add('nbrEtoile', ChoiceType::class, [
                    'choices' => [
                        'Rate Us 1/5' => 'Rate Us 1/5',
                        'Rate Us 2/5' => 'Rate Us 2/5',
                        'Rate Us 3/5' => 'Rate Us 3/5',
                        'Rate Us 4/5' => 'Rate Us 4/5',
                        'Rate Us 5/5' => 'Rate Us 5/5'
                    ],
                    'label' => 'Rate Us',
                    'expanded' => true,
                    'multiple' => false,
                    'constraints' => [
                        new NotBlank(['message' => 'Please select a rating.']),
                    ],
                    'choice_label' => function ($choice, $key, $value) {
                        // Générer les étoiles selon la valeur
                        $rating = intval(substr($value, -3, 1));  // Extrait le chiffre de X/5
                        $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                        return $stars;
                    },
                ]);
                
                
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}

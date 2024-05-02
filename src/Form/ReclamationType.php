<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;


class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('reponse', TextType::class, [
            'label' => 'Response',
            'constraints' => [
                new NotBlank(['message' => 'Please enter a response.']),
                new Length([
                    'min' => 10,
                    'max' => 255,
                    'minMessage' => 'The response must be at least {{ limit }} characters long.',
                    'maxMessage' => 'The response cannot be longer than {{ limit }} characters.',
                ]),
            ],
        ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'In Progress' => 'In Progress',
                    'Finished' => 'Finished',
                ],
                'placeholder' => 'Choose an option',
                'label' => 'Status', 
                'attr' => ['class' => 'form-control'],
               
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}

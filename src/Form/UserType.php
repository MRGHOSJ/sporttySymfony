<?php

namespace App\Form;
//use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', null, [
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('prenom', null, [
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
            ->add('email', TextType::class,[
                'constraints' => [
                    new  Assert\NotBlank(),
                   new Assert\Email(),
                   
                  // new Unique(['entityClass' => 'App\Entity\User','fields' => 'email', 'message' => 'This email is already taken.']),
                
                ],
              
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 6]),
                    
                ],
            ])
            
            ->add('role', HiddenType::class, [
                'data' => 'ADHERANT'
            ,
                
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\User',
        ]);
    }
}

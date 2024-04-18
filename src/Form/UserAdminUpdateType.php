<?php

namespace App\Form;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
class UserAdminUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', null, [
            'constraints' => [
             
            ],
        ])
        ->add('prenom', null, [
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('email',null,[
            'constraints' => [
                new  Assert\NotBlank(),
               new Assert\Email(),
            
            ],
          
        ])
        
        ->add('role', ChoiceType::class, [
            'label' => 'Rôle',
            'choices' => [
                'ADHERANT' => 'ADHERANT',
                'Admin' => 'ADMIN',
                'Coach' => 'COACH',
            ],
            'attr' => [
                'class' => 'form-control',
            ],
            'constraints' => [
                new Assert\NotBlank(),
            ]
])
->add('imageFile', FileType::class, [
    'label' => 'Image (JPEG, PNG, GIF)',
    'mapped' => false, // Ne pas mapper directement à l'entité User
    'required' => false, // L'image n'est pas obligatoire
    'constraints' => [
        new Assert\File([
            'maxSize' => '1024k',
            'mimeTypes' => [
                'image/jpeg',
                'image/png',
                'image/gif',
            ],
            'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF)',
        ])
    ],
])
;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

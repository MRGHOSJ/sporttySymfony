<?php 
namespace App\Form;
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
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints\NotBlank;
class UserAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
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
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 6]),
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
                    new NotBlank(),
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
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

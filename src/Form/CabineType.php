<?php

namespace App\Form;

use App\Entity\Cabine;
use App\Entity\SaleDeSport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CabineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCabine', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Nom',
            ])
            ->add('capacite', IntegerType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Capacite',
            ])
            ->add('hasVr', CheckboxType::class, [
                'label' => 'Has Vr',
            ])
            ->add('image', FileType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Image',
                'required' => false,
                'mapped' => false,
            ])

            ->add('idSalle', EntityType::class, [
                'class' => SaleDeSport::class,
                'choice_label' => 'nomSalle',
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Salle de Sport',
            ])            
            ->add('Submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-user btn-block mt-4'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cabine::class,
        ]);
    }
}

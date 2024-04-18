<?php

namespace App\Form;

use App\Entity\SaleDeSport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleDeSportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomSalle', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Nom Salle',
            ])
            ->add('descr', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Description Salle',
            ])
            ->add('lieuSalle', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Lieu Salle',
            ])
            ->add('numSalle', IntegerType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Numero Salle',
            ])
            ->add('lienvideo', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Lien Salle',
            ])
            ->add('image', FileType::class,[
                'attr' => ['class' => 'form-control form-control-user'],
                'required'=>false,
                 'mapped'=>false,
            ])
            ->add('location', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Location Salle',
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-user btn-block mt-4'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SaleDeSport::class,
        ]);
    }
}

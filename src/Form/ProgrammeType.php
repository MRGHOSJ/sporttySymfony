<?php

namespace App\Form;

use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Name',
            ])
            ->add('description', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Description',
            ])
            ->add('duree', IntegerType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Duration',
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-user btn-block mt-4'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}

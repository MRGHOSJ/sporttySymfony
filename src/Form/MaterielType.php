<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\Stock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Nom',
            ])
            ->add('categorie', ChoiceType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'choices' => [
                    'Supplement' => 'Supplement',
                    'Accessories' => 'Accessories',
                    'Other' => 'Other',
                ],
                'label' => 'Categorie'
            ])
            ->add('qte', IntegerType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Quantite',
            ])
            ->add('image', FileType::class,[
                'attr' => ['class' => 'form-control form-control-user'],
                'required'=>false,
                 'mapped'=>false,
            ])
            ->add('video', TextType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Video',
            ])
            ->add('idStock', EntityType::class, [
                'class' => Stock::class,
                'choice_label' => 'id',
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Stock',
            ])     
            ->add('Submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-user btn-block mt-4'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}

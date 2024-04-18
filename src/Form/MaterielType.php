<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Form\Transformer\ImageTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class MaterielType extends AbstractType
{
    public function __construct(ImageTransformer $imageTransformer)
    {
        $this->imageTransformer = $imageTransformer;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('categorie',ChoiceType::class,[
                'label' => 'Catégorie',
                'choices' => [
                    'Appareils de cardio-training' => 'Appareils de cardio-training',
                    'Appareils de musculation' => 'Appareils de musculation',
                    'Équipements de musculation fonctionnelle' => 'Équipements de musculation fonctionnelle',
                    'Zone de poids libres '=>'Zone de poids libres ',
                    'Accessoires de fitness'=>'Accessoires de fitness'
                ],
            ])

            ->add('qte')
            ->add('image', FileType::class, [
                'required' => false,
                'data_class' => null,
                'attr' => ['accept' => 'image/*'],
                'label' => 'Image',
            ])
            ->add('video')
            ->add('idStock')
        ;
        $builder->get('image')->addModelTransformer($this->imageTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}

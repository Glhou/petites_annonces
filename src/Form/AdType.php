<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class, [
                'attr' =>[
                    'class'=>'no-resize',
                    'style'=>'height: 300px;'
                ]
            ])
            ->add('location')
            ->add('type',ChoiceType::class, [
                'choices'  => [
                    'Objet trouvé' => 1,
                    'Objet perdu' => 2,
                    'Vente' => 3,
                    'Recherche' => 4,
                    'Tips' => 5,
                ],])
            ->add('resolved', ChoiceType::class,[
                'choices' =>[
                    'Non' => false,
                    'Oui' => true,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\AdSearch;
use Doctrine\DBAL\Types\BooleanType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class,[
                'required' => false,
                'label'=>false,
                'choices'  => [
                    'Choisir le type d\'annonce'=> null,
                    'Objet trouvé' => 1,
                    'Objet perdu' => 2,
                    'Vente' => 3,
                    'Recherche' => 4,
                    'Tips' => 5,
                ],
                'attr' =>[
                    'placeholder' => "Type d'annonce",
                ],
            ])
            ->add('name', TextType::class,[
                'required' => false,
                'label'=>false,
                'attr' => [
                    'placeholder' => "Nom de l'annonce",
                ],
            ])
            ->add('resolved', CheckboxType::class,[
                'required' => false,
                'label'=>'Résolu',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AdSearch::class,
            'method' => 'get',
            'csrf_protection' => false,
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}

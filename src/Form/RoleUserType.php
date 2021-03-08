<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class RoleUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
            ->add('roles', CollectionType::class, [
                'entry_type' => ChoiceType::class,
                'required' => true,
                'label' => false,
                'entry_options'=>[
                    'choices' => [
                        'User' => "ROLE_USER",
                        'Modo' => "ROLE_MODO",
                        'Admin' => "ROLE_ADMIN",
                    ],
                    // il fait la conversion en array tout seul avec le CollectionType (affiche n'importe quoi
                    // si on met des array genre ça fait des groupes)
                ],
            ]);
    }

    // ici on vérifie pas forcément les conditions de mot de passe car inutile et ça bloque en plus car
    // une fois encodé le mot de passe est trop long
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => false,
        ]);
    }
}

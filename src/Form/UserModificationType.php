<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class UserModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            // Tu vas devoir modifier une contraite et rajoute quelque chose.
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'empty_data' => null,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'max' => 24,
                        'minMessage' => 'Mot de passe trop court',
                        'maxMessage' => 'Mot de passe trop long',
                    ])
                ]
            ])
            ->add('firstname')
            ->add('lastname')
            ->add('profilePicture', FileType::class, [
                'label' => 'Photo de profil',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control-file']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class, [
                'required' => false,
                'label' => ' ',

                'attr' => [
                    'placeholder' => 'Prénom*',
                    'class' => 'bg-gray-100 w-full text-gray-800 text-sm px-4 py-4 focus:bg-transparent outline-primary transition-all',
                ],
                

            ])
            ->add('lastName',TextType::class, [
                'required' => false,
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'Nom*',
                    'class' => 'bg-gray-100 w-full text-gray-800 text-sm px-4 py-4 focus:bg-transparent outline-primary transition-all',
                ],

            ])
            ->add('email',EmailType::class, [
                'required' => false,
                'label' => ' ',

                'attr' => [
                    'placeholder' => 'Email*',
                    'class' => 'bg-gray-100 w-full text-gray-800 text-sm px-4 py-4 focus:bg-transparent outline-primary transition-all',
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'placeholder' => 'Mot de passe*',
                        'class' => 'bg-gray-100 w-full text-gray-800 text-sm px-4 py-4 focus:bg-transparent outline-primary transition-all',
                    ],
                ],
                'required' => false,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le champ Mot de passe est obligatoire',
                        ]),
                       
                        new Regex(
                            [
                                'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@\$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/',
                                'message' => 'Au moins 8 caractères avec (abc ABC 012 @\$!%*#?&)',
                                    ]
                        )
                    ],
                    'label' => ' ',
                ],
                'second_options' => [
                    'label' => ' ',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez confirmer votre mot de passe',
                        ]),
                    ],
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            
            ->add('submit', SubmitType::class, [
                'label' => 'Créer un compte',
                'attr' => [
                    'class' => 'w-full mt-3 shadow-xl py-3 px-4 text-sm font-semibold rounded-md text-white bg-primary hover:bg-secondary focus:outline-none',
            
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            

        ]);

    }
}

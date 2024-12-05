<?php

namespace App\Form;

use App\Entity\Candidat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;


class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstName',TextType::class,[
            'mapped' => false,
            'label' => 'Prénom',
            'disabled' => true
        ])
        ->add('lastName',TextType::class,[
            'mapped' => false,
            'disabled' => true,
            'label' => 'Nom',
        ])
        ->add('email', TextType::class,
            [
                'mapped' => false,
                'label' => 'Email',
                'disabled' => true

            ]
        )
        ->add('phoneNumber',TelType::class,[
            'label' => 'Téléphone',
            'attr' => ['placeholder' => 'Numéro de téléphone'],
            
            'constraints' => [
                        new NotBlank([
                            'message' => 'Le numéro de téléphone est obligatoire',
                        ]),
                    ],
        ])
        ->add('ville',ChoiceType::class,[
            'choices'  => [
                'Choisir votre ville' => null,
                'Ariana' => 'Ariana',
                'Ben Arous' => 'Ben Arous',
                'Manouba' => 'Manouba',
                'Nabeul' => 'Nabeul',
                'Zaghouan' => 'Zaghouan',
                'Bizerte' => 'Bizerte',
                'Beja' => 'Béja',
                'Jendouba' => 'Jendouba',
                'Kef' => 'Le Kef',
                'Siliana' => 'Siliana',
                'Kairouan' => 'Kairouan',
                'Kasserine' => 'Kasserine',
                'Sidi Bouzid' => 'Sidi Bouzid',
                'Sousse' => 'Sousse',
                'Monastir' => 'Monastir',
                'Mahdia' => 'Mahdia',
                'Sfax' => 'Sfax',
                'Gafsa' => 'Gafsa',
                'Tozeur' => 'Tozeur',
                'Kebili' => 'Kébili',
                'Gabes' => 'Gabès',
                'Medenine' => 'Médenine',
                'Tataouine' => 'Tataouine',
                'Tunis' => 'Tunis'
            ],
            'label' => 'Ville',
            'attr' => ['class' => 'py-1 px-2 w-full flex',],
            'constraints' => [
                new NotBlank([
                    'message' => 'La ville est obligatoire',    
                ])
                ],
            ])
            ->add('adresse',TextType::class,[
                'label' => 'Adresse',
                'attr' => ['placeholder' => 'Votre adresse'],


                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'adresse est obligatoire',
                    ]),
                ],
            ])

       ->add('cvFile', VichFileType::class, [
            'required' => true,
            'help' => 'Veuillez télécharger votre CV au format PDF ou Word',
            'label' => 'Télécharger votre CV',
            'constraints' => [
                new NotBlank([
                    'message' => 'Le CV est obligatoire',
                ]),
            ],
        ])
        ->add('submit',SubmitType::class,[
            'label' => 'Envoyez votre candidature',
            'attr' => [
                'class' => 'w-full mt-3 shadow-xl py-3 px-4 text-sm font-semibold rounded-md text-white bg-primary hover:bg-secondary focus:outline-none',
            ],  
        ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('PseudoNom', TextType::class, [
                'attr' => [
                    'placeholder' => 'pseudo nom',
                    "class" => "form-control",
                ],
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => 'entrer votre nom',
                    "class" => "form-control",
                ],
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'placeholder' => 'entrer votre prennom',
                    "class" => "form-control",
                ],
            ])
            ->add('adresse', TextType::class, [
                'attr' => [
                    'placeholder' => 'entrer votre adresse',
                    "class" => "form-control",
                ],
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    "class" => "form-control",
                ],
            ])
            ->add('sexe', ChoiceType::class, [
                'attr' => [
                    "class" => "d-flex gap-2 form-control",
                ],
                'choices' => [
                    'Male' => 'M',
                    'Female' => 'F',
                ],
                'choice_attr' => function ($choice, $key, $value) {
                    // Define classes for each choice
                    return ['class' => 'form-check-input ' . $value];
                },
                'expanded' => true,
                'label' => 'Sexe',
                'multiple' => false,
            ])

            ->add('profession', ChoiceType::class, [
                'choices' => [
                    'Member' => 'member',
                    'Professeur' => 'professeur',

                ],
                'attr' => [
                    "class" => "form-select",
                ],
                'placeholder' => 'Profession',
                'multiple' => false
            ])

            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,

                'attr' => [
                    'autocomplete' => 'new-password',
                    "class" => "form-control",
                    'placeholder' => '********',

                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

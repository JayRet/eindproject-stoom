<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NoSuspiciousCharacters;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', [
                new NotBlank([
                    'message' => 'Please enter a name',
                ]),
                new Regex([
                    'pattern' => '/^[a-zA-Z0-9\s]+$/',
                    'message' => 'Your name can only contain letters, numbers and spaces',
                ]),
                new Length([
                    'max' => 64,
                    'maxMessage' => 'Please limit your name to {{ limit }} characters',
                ])
            ])
            ->add('description', TextType::class, [
                new NoSuspiciousCharacters([
                    'message' => 'Some characters in the description cannot be used',
                ]),
                new Length([
                    'max' => 1000,
                    'maxMessage' => 'Please limit your description to {{ limit }} characters',
                ])
            ])
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image'
                    ]),
                ]
            ])
            ->add('url', [
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Your URL can only be {{ limit }} long',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}

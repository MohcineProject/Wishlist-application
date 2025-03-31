<?php
// Created by Firas Bouzazi and Mohammed Oun 

namespace App\Form;

use App\Entity\PurchaseProof;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PurchaseProofType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('congratsText', TextType::class, [
                'label' => 'Congratulations Message'
            ])
            ->add('imagePath', FileType::class, [
                'label' => 'Upload Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Purchase Proof'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PurchaseProof::class,
        ]);
    }
}

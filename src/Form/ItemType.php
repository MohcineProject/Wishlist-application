<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Article name'])
            ->add('url', TextType::class, ['label' => 'Buy URL'])
            ->add('price', NumberType::class, ['label' => 'Price'])
            ->add('description', TextType::class, ['label' => 'Description'])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Upload a new image', // ✅ Change le label pour éviter la confusion
                'required' => false, // ✅ Permet de ne pas forcer un nouvel upload
                'allow_delete' => false, // ✅ Désactive la suppression automatique
                'download_uri' => false, // ✅ Désactive le lien de téléchargement
                'image_uri' => false, // ✅ Empêche l'affichage de l'image dans l'input
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'csrf_protection' => true, // ✅ Active la protection CSRF
            'csrf_field_name' => '_token', // ✅ Définit le nom du champ CSRF
            'csrf_token_id'   => 'submit', // ✅ Identifie le token
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostUpdateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('title', TextType::class, ['label' => "Titre"])
                ->add('body', TextareaType::class, ['label' => 'Contenu de l\'article'])
                ->add('img', TextType::class, ['label' => 'Image de l\'article'])
                ->add('author', TextType::class, ['label' => 'Nom de l\'auteur'])
//                ->add('categories', ChoiceType::class, ['choices' => [
//
//                ]])
                ->add("send", SubmitType::class, ['label' => "Soumettre"]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}

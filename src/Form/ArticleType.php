<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Titre de l\'article',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Contenu :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Contenu de l\'article',
                    'rows' => 10
                ]
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,

            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,

        ]);
    }
}

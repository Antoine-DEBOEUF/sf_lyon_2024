<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            ->add('images', CollectionType::class, [
                'label' => false,
                'required' => false,
                'entry_type' => ArticleImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
            ])

            ->add('categories', EntityType::class, [
                'label' => 'Catégorie(s) :',
                'placeholder' => 'Sélectionnez une catégorie',
                'class' => Categorie::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.enable = :enable')
                        ->setParameter('enable', true)
                        ->orderBy('c.title', 'ASC');
                },
                'choice_label' => 'title',
                'required' => false,
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,
                'autocomplete' => true,

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
            'sanitize_html' => true,

        ]);
    }
}

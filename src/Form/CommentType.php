<?php

namespace App\Form;


use App\Entity\ArticleCommentary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Note', ChoiceType::class, [
                'label' => 'Note :',
                'required' => true,
                'choices' => [
                    '0/5' => 0,
                    '1/5' => 1,
                    '2/5' => 2,
                    '3/5' => 3,
                    '4/5' => 4,
                    '5/5' => 5,
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Contenu du commentaire',
                    'rows' => 10
                ]
            ])
            ->add('rgpd', CheckboxType::class, [
                'label' => 'Accepter les CGU',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Vous devez accepter les CGU pour poster un commentaire.'])
                ]
            ]);


        if ($options['isAdmin']) {
            $builder
                ->add('enable', CheckboxType::class, [
                    'label' => 'Actif',
                    'required' => false,

                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticleCommentary::class,
            'sanitize_html' => true,
            'isAdmin' => false,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Quote;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Votre titre :',
                'attr' => [
                    'placeholder' => 'Saisir un titre'
                ],
            ])
            ->add('text', null, [
                'label' => 'Votre anecdote :',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Saisir une anecdote'
                ],
            ])
            ->add('source', null, [
                'label' => 'Votre source :',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Saisir une source'
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Catégorie',
                'required' => true,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quote::class,
        ]);
    }
}

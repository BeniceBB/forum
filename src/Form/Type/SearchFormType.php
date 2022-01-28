<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('search', SearchType::class, ['attr' => ['class' => 'form-control', 'placeholder' => 'placeholder.search'], 'required' => false]);
        $builder->add('type', ChoiceType::class, [
            'choices' => [
                'label.all' => 'all',
                'label.title' => 'title',
                'label.description' => 'short_description',
                'label.post' => 'body',
                'label.author' => 'user',
                ],
            'expanded' => true,
            'multiple' => true,
        ]);
        $builder->add('postsPerPage', ChoiceType::class, [
            'choices' => [
                5 => 5,
                10 => 10,
                25 => 25,
                50 => 50
            ],
        ]);
    }
}
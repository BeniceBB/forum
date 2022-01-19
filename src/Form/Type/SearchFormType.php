<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('search', SearchType::class, ['attr' => ['class' => 'form-control']]);
        $builder->add('type_all', CheckboxType::class, [
            'label' => 'all',
            'checked' => true,
            'name' => 'all',
            'id' => 'all',
        ]);
        $builder->add('amountfilter', ChoiceType::class, ['choices' => [5, 10, 25, 50]]);
    }

}
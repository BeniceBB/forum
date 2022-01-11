<?php

namespace App\Form\Type;

use App\Entity\User;
use App\Entity\Blog;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, ['attr' => ['class' => 'form-control']]);
        $builder->add('shortDescription', TextType::class, ['attr' => ['class' => 'form-control']]);
        $builder->add('body', TextareaType::class, ['attr' => ['class' => 'form-control']]);
//        $builder->add('user', HiddenType::class, ['data_class' => null]);

//        $builder->add('user', EntityType::class, ['class' => User::class, 'choice_label' => 'username']);
//        $builder->add('author', AuthorFormType::class);
//        $builder->add('imageFile', FileType::class, [
//            'attr'     => ['class' => 'form-control',],
//            'mapped' => false,
//        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Blog::class,
            ]
        );
    }
}
<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'label'=>'Başlık'
            ])
            ->add('content',TextareaType::class,[
                'required'=>false,
                'attr'=>[
                    'data-emre'=>'here'
                ]
            ])
            ->add('category',EntityType::class,[
                'label'=>'Kategori',
                'required'=>false,
                'class'=>Category::class
            ])
            ->add('submit',SubmitType::class,[])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
            'attr'=>['novalidate'=>'novalidate']
        ]);
    }
}

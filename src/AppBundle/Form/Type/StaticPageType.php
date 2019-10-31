<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\StaticPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaticPageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('slug', TextType::class)
            ->add('enabled', CheckboxType::class)
            ->add('orderNum', IntegerType::class)
            ->add('metaKeys', TextType::class)
            ->add('metaDescription', TextType::class)
            ->add('content', TextareaType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => StaticPage::class]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'eshop_shopbundle_staticpage';
    }
}

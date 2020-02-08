<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Measure;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('slug', TextType::class)
            ->add('description', TextareaType::class)
            ->add('price', NumberType::class)
            ->add('category', EntityType::class, [
                'required' => true,
                'multiple' => false,
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('manufacturer', EntityType::class, [
                'required' => true,
                'multiple' => false,
                'class' => Manufacturer::class,
                'choice_label' => 'name'
            ])
            ->add('quantity', IntegerType::class)
            ->add('metaKeys', TextType::class)
            ->add('metaDescription', TextType::class)
            ->add('measure', EntityType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'class' => Measure::class,
                'choice_label' => 'name'
            ])
            ->add('measureQuantity', IntegerType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Product::class]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'eshop_shopbundle_product';
    }
}

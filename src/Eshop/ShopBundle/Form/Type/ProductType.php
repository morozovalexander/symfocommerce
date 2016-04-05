<?php

namespace Eshop\ShopBundle\Form\Type;

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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('slug', TextType::class)
            ->add('description', TextareaType::class)
            ->add('price', NumberType::class)
            ->add('category', EntityType::class, array(
                'required'  => true,
                'multiple' => false,
                'class' => 'Eshop\ShopBundle\Entity\Category',
                'choice_label' => 'name'
            ))
            ->add('manufacturer', EntityType::class, array(
                'required'  => true,
                'multiple' => false,
                'class' => 'Eshop\ShopBundle\Entity\Manufacturer',
                'choice_label' => 'name'
            ))
            ->add('quantity', IntegerType::class)
            ->add('metaKeys', TextType::class)
            ->add('metaDescription', TextType::class)
            ->add('measure', EntityType::class, array(
                'required'  => true,
                'multiple' => false,
                'expanded' => false,
                'class' => 'Eshop\ShopBundle\Entity\Measure',
                'choice_label' => 'name'))
            ->add('measureQuantity', IntegerType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eshop\ShopBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'eshop_shopbundle_product';
    }
}

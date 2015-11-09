<?php

namespace Eshop\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('category', 'entity', array(
                'required'  => true,
                'multiple' => false,
                'class' => 'Eshop\ShopBundle\Entity\Category',
                'property' => 'name'
            ))
            ->add('manufacturer', 'entity', array(
                'required'  => true,
                'multiple' => false,
                'class' => 'Eshop\ShopBundle\Entity\Manufacturer',
                'property' => 'name'
            ))
            ->add('quantity')
            ->add('metaKeys')
            ->add('metaDescription')
            ->add('measure', 'entity', array(
                'required'  => true,
                'multiple' => false,
                'expanded' => false,
                'class' => 'Eshop\ShopBundle\Entity\MEasure',
                'property' => 'name'))
            ->add('measureQuantity')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eshop\ShopBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eshop_shopbundle_product';
    }
}

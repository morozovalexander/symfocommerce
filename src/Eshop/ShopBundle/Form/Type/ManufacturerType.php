<?php

namespace Eshop\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ManufacturerType extends AbstractType
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
            ->add('file', FileType::class, array('required' => false))
            ->add('metaKeys', TextType::class)
            ->add('metaDescription', TextType::class)
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eshop\ShopBundle\Entity\Manufacturer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eshop_shopbundle_manufacturer';
    }
}

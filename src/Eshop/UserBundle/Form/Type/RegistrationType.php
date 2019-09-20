<?php

namespace Eshop\UserBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class,
                ['label' => 'registration.firstname', 'translation_domain' => 'ShopBundle'])
            ->add('lastname', TextType::class,
                ['label' => 'registration.lastname', 'translation_domain' => 'ShopBundle'])
            ->add('phone', TextType::class,
                ['label' => 'registration.phone', 'translation_domain' => 'ShopBundle'])
            ->add('address', TextareaType::class,
                ['label' => 'registration.address', 'translation_domain' => 'ShopBundle']);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}

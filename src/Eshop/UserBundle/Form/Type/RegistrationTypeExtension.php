<?php

namespace Eshop\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationTypeExtension extends AbstractTypeExtension
{
    /**
     * @return string The name of the type being extended
     */
    public function getExtendedType(): string
    {
        return RegistrationFormType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
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
}

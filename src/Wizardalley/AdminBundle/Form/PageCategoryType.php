<?php

namespace Wizardalley\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PageCategoryType
 *
 * @package Wizardalley\AdminBundle\Form
 */
class PageCategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('fileLogo')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Wizardalley\CoreBundle\Entity\PageCategory'
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizardalley_corebundle_pagecategory';
    }
}

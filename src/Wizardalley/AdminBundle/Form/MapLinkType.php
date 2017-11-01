<?php

namespace Wizardalley\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wizardalley\CoreBundle\Entity\MapLink;

/**
 * Class MapLinkType
 *
 * @package Wizardalley\AdminBundle\Form
 */
class MapLinkType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('display')
            ->add('link')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'   => MapLink::class,
                'allow_add'    => true,
                'allow_delete' => true
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizardalley_corebundle_maplink';
    }
}

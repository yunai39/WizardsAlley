<?php

namespace Wizardalley\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class InformationBilletType
 *
 * @package Wizardalley\AdminBundle\Form
 */
class InformationBilletType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add(
                'content',
                'textarea',
                [
                    'attr' => [
                        'class'      => 'tinymce',
                        'data-theme' => 'bbcode' // Skip it if you want to use default theme
                    ]
                ]
            )
            ->add(
                'datePublicationBillet',
                'date',
                [
                    'format' => 'yyyy-MM-dd'
                ]
            )
            ->add(
                'commentsEnabled',
                'checkbox',
                [
                    'required' => false,
                ]
            )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Wizardalley\CoreBundle\Entity\InformationBillet'
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizardalley_adminbundle_informationbillet';
    }
}

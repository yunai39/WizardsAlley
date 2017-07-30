<?php

namespace Wizardalley\PublicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PublicationType
 *
 * @package Wizardalley\PublicationBundle\Form
 */
class PublicationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
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
                    'images',
                    'collection',
                    [
                        'type'         => new ImageType(),
                        'allow_add'    => true,
                        'allow_delete' => true,
                        'by_reference' => false,
                        'required'     => false
                    ]
                )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'Wizardalley\CoreBundle\Entity\Publication']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizardalley_publicationbundle_publication';
    }

}

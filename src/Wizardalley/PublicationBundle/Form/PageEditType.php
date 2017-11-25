<?php

namespace Wizardalley\PublicationBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PageEditType
 *
 * @package Wizardalley\PublicationBundle\Form
 */
class PageEditType extends AbstractType
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
                'description',
                'textarea',
                [
                    'attr' => [
                        'class' => 'tinymce',
                        'data-theme' => 'bbcode' // Skip it if you want to use default theme
                    ]
                ]
            )
            ->add(
                'urlFacebook',
                'url',
                ['required' => false]
            )
            ->add(
                'fileProfile',
                'file',
                ['required' => false]
            )
            ->add(
                'fileCouverture',
                'file',
                ['required' => false]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => 'Wizardalley\CoreBundle\Entity\PageCategory'
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
                'data_class' => 'Wizardalley\CoreBundle\Entity\Page'
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizardalley_publicationbundle_page_edit';
    }

}

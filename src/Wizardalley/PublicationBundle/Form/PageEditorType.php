<?php

namespace Wizardalley\PublicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PageEditorType
 *
 * @package Wizardalley\PublicationBundle\Form
 */
class PageEditorType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'editors', 'collection', [
                    'type'         => new EditorType(),
                    'allow_add'    => true,
                    'allow_delete' => true,
                ]
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'Wizardalley\CoreBundle\Entity\Page']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizardalley_publicationbundle_page_editor';
    }
}

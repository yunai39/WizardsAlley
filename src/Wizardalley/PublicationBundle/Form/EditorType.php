<?php

namespace Wizardalley\PublicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class EditorType
 *
 * @package Wizardalley\PublicationBundle\Form
 */
class EditorType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('username', 'text', ['read_only' => true])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'Wizardalley\CoreBundle\Entity\WizardUser',]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizardalley_publicationbundle_page_editor_user';
    }
}

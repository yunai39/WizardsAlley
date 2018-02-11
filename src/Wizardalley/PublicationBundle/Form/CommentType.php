<?php

namespace Wizardalley\PublicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CommentType
 *
 * @package Wizardalley\PublicationBundle\Form
 */
class CommentType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'content', 'textarea', [
                'label'    => false,
                'required' => true,
                'attr'     => ['placeholder' => 'wizard.comment.placeholder',]
            ]
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Wizardalley\CoreBundle\Entity\CommentPublication'
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizardalley_publicationbundle_comment_publication';
    }
}

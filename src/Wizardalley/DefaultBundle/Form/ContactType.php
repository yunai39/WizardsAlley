<?php

namespace Wizardalley\DefaultBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ContactType
 *
 * @package Wizardalley\DefaultBundle\Form
 */
class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                ['label' => 'wizard.contact.label.nom']
            )
            ->add(
                'email',
                'email',
                ['label' => 'wizard.contact.label.email']
            )
            ->add(
                'message',
                'textarea',
                ['label' => 'wizard.contact.label.message']
            )
            //->add('captcha','captcha')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'mapped' => 'false'
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizard_contact_form';
    }
}

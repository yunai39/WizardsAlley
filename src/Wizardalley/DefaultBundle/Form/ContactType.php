<?php

namespace Wizardalley\DefaultBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', 'text',array('label' => 'wizard.contact.label.nom'))
                ->add('email', 'email',array('label' => 'wizard.contact.label.email'))
                ->add('message', 'textarea',array('label' => 'wizard.contact.label.message'))
                //->add('captcha','captcha')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'mapped' => 'false'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizard_contact_form';
    }
}

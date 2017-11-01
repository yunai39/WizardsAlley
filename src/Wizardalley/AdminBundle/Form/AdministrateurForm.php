<?php

namespace Wizardalley\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AdministrateurForm
 *
 * @package Wizardalley\AdminBundle\Form
 */
class AdministrateurForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('lastname')
            ->add('firstname')
            ->add('sexe', 'choice', [
                'choices' => [0 => 'M', 1 => 'F']
            ])
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wizardalley\CoreBundle\Entity\WizardUser'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizardalley_adminbundle_informationbillet';
    }
}

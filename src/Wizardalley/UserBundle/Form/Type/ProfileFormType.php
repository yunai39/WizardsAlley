<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wizardalley\UserBundle\Form\Type; 

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ProfileFormType
 * @package Wizardalley\UserBundle\Form\Type
 */
class ProfileFormType extends Form
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('facebook','text',array(
                'label' =>'wizard.register.label.facebook',
                )
            )
            ->add('twitter','text',array(
                'label' =>'wizard.register.label.twitter',
                )
            )

        ;

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WizardalleyCoreBundle:WizardUser',
        ));
    }

    /**
     * @return string
     */
    public function getName()     
    {         
        return 'wizard_user_profile_edit';     
    }
}

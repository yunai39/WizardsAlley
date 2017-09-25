<?php

namespace Wizardalley\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseForm;

/**
 * Class ProfileFormType
 *
 * @package Wizardalley\UserBundle\Form\Type
 */
class ProfileFormType extends BaseForm
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->remove('email')
            ->remove('current_password')
            ->add(
                'lastname',
                'text',
                [
                    'label' => 'wizard.register.label.lastname',
                ]
            )
            ->add(
                'firstname',
                'text',
                [
                    'label' => 'wizard.register.label.firstname',
                ]
            )
            ->add(
                'small_description',
                'text',
                array(
                    'label' =>'wizard.register.label.small_description',
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' =>'wizard.register.label.description',
                )
            )
            ->add(
                'facebook',
                'text',
                [
                    'label' => 'wizard.register.label.facebook',
                    'required' => false
                ]
            )
            ->add(
                'twitter',
                'text',
                [
                    'label' => 'wizard.register.label.twitter',
                    'required' => false
                ]
            )
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizard_user_profile_edit';
    }
}

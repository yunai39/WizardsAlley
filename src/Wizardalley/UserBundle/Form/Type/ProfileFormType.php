<?php

namespace Wizardalley\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseForm;
use Symfony\Component\Validator\Constraints\Length;

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
                    'attr'  => [
                        'max' => 255
                    ]
                ]
            )
            ->add(
                'firstname',
                'text',
                [
                    'label' => 'wizard.register.label.firstname',
                    'attr'  => [
                        'max' => 255
                    ]
                ]
            )
            ->add(
                'small_description',
                'text',
                [
                    'label' => 'wizard.register.label.small_description',
                    'attr'  => [
                        'max' => 128
                    ]
                ]
            )
            ->add(
                'description',
                null,
                [
                    'label' => 'wizard.register.label.description',
                ]
            )
            ->add(
                'facebook',
                'text',
                [
                    'label'    => 'wizard.register.label.facebook',
                    'required' => false,
                    'attr'     => [
                        'max' => 255
                    ]
                ]
            )
            ->add(
                'twitter',
                'text',
                [
                    'label'    => 'wizard.register.label.twitter',
                    'required' => false,
                    'attr'     => [
                        'max' => 255
                    ]
                ]
            )
            ->add(
                'youtube',
                'text',
                [
                    'label'    => 'wizard.register.label.youtube',
                    'required' => false,
                    'attr'     => [
                        'max' => 255
                    ]
                ]
            )
            ->add(
                'instagram',
                'text',
                [
                    'label'    => 'wizard.register.label.instagram',
                    'required' => false,
                    'attr'     => [
                        'max' => 255
                    ]
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

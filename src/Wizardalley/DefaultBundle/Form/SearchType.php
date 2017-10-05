<?php

namespace Wizardalley\DefaultBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = [
            'page'        => 'wizard.search.label.page',
            'publication' => 'wizard.search.label.publication',
        ];
        if ($options[ 'isOnline' ]) {
            $choices[ 'user' ] = 'wizard.search.label.user';
        }
        $builder
            ->add(
                'searchType',
                'choice',
                [
                    'label'   => false,
                    'choices' => $choices
                ]
            )
            ->add(
                'field',
                'text',
                [
                    'label' => false,
                    'attr'  => ['placeholder' => 'wizard.search.label.field']
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
                'mapped'   => 'false',
                'isOnline' => false
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizard_search_form';
    }
}

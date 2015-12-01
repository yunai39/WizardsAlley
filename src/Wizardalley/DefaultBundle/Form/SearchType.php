<?php

namespace Wizardalley\DefaultBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('searchType', 'choice', [
                    'label' => 'wizard.search.type.label',
                    'choices' => [
                        'friend' => 'wizard.search.label.friend',
                        'page' => 'wizard.search.label.page',
                        'publication' => 'wizard.search.label.publication',
                    ]
                ])
                ->add('field', 'text',['label' => 'wizard.search.label.field'])
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
        return 'wizard_search_form';
    }
}

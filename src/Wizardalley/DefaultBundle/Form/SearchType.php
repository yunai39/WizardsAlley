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
                ->add('friend', 'checkbox',array('label' => 'wizard.search.label.friend'))
                ->add('page', 'checkbox',array('label' => 'wizard.search.label.page'))
                ->add('publication', 'checkbox',array('label' => 'wizard.search.label.publication'))
                ->add('field', 'text',array('label' => 'wizard.search.label.field'))
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

<?php

namespace Wizardalley\PublicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DeletePublicationType
 *
 * @package Wizardalley\PublicationBundle\Form
 */
class DeletePublicationType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('id', 'hidden')
                ->add('sumbit', 'submit', ['label' => 'wizard.utility.delete'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'mapped' => false
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'wizardalley_publicationdelete';
    }
}

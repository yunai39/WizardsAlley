<?php

namespace Wizardalley\PublicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SmallPublicationType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('content', 'textarea')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Wizardalley\PublicationBundle\Entity\SmallPublication'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'wizardalley_publicationbundle_add_small_publication';
    }

}
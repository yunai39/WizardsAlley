<?php

namespace Wizardalley\PublicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageEditorType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('editors', 'collection', array(
                    'type' => new EditorType(),
                    'allow_add'    => true,
                    'allow_delete' => true,
                    
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Wizardalley\PublicationBundle\Entity\Page'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'wizardalley_publicationbundle_page_editor';
    }

}

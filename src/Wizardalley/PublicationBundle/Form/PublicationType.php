<?php

namespace Wizardalley\PublicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wizardalley\PublicationBundle\Form\ImageType;

class PublicationType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title')
                ->add('content', 'textarea', array(
                    'attr' => array(
                        'class' => 'tinymce',
                        'data-theme' => 'bbcode' // Skip it if you want to use default theme
                    )
                ))
                ->add('images', 'collection', array(
                    'type' => new ImageType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    ))
                ->add('page', 'entity', array(
                    'class' => 'WizardalleyPublicationBundle:Page'
                ));
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Wizardalley\PublicationBundle\Entity\Publication'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'wizardalley_publicationbundle_publication';
    }

}

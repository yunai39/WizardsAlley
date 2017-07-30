<?php

namespace Wizardalley\PublicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description')
                ->add(
                    'file',
                    'vich_image',
                    [
                        'label' => 'wizard.publication.images.label',
                    ]
                )
                ->add(
                    'delete',
                    'button',
                    [
                        'attr' => [
                            'class' => 'delete-img'
                        ]
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
                'data_class' => 'Wizardalley\CoreBundle\Entity\ImagePublication'
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizardalley_publicationbundle_image';
    }

}

<?php

namespace Wizardalley\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

class ImagePublicationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ) {
        $builder
            ->add('description')
            ->add('file','vich_image');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Wizardalley\CoreBundle\Entity\ImagePublication'
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizardalley_publicationbundle_image';
    }

}

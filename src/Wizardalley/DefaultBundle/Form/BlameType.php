<?php

namespace Wizardalley\DefaultBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BlameType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                HiddenType::class
            )
            ->add(
                'id',
                HiddenType::class
            )
            ->add(
                'comment',
                TextareaType::class,
                ['label' => 'wizard.blame.label.comment']
            )
            ->add('sumbit', 'submit', ['label' => 'wizard.utility.blame'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Wizardalley\CoreBundle\Entity\Blame'
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizard_blame_form';
    }
}

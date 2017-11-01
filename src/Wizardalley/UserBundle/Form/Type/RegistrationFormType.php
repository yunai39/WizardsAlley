<?php
namespace Wizardalley\UserBundle\Form\Type;  

use Symfony\Component\Form\FormBuilderInterface; 
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType {     

    public function buildForm(FormBuilderInterface $builder, array $options)     
    {         
        parent::buildForm($builder, $options);         

        // add your custom field        
        $builder->add('lastname','text',array(
                'label' =>'wizard.register.label.lastname',
            ))
            ->add('firstname','text',array(
                'label' =>'wizard.register.label.firstname',
            ))
            ->add('accept_cgv', 'checkbox')
            ->add('sexe','choice', array(
                'label'     => 'wizard.register.label.sexe',
                'choices'   => array(
                    '0'   => 'wizard.register.field.sexe.male',
                    '1'   => 'wizard.register.field.sexe.female',
                ))
            );
    }

    public function getName()     
    {         
        return 'wizard_user_registation';
    } 
}
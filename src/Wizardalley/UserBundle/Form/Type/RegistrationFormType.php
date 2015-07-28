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
        ));       
        $builder->add('firstname','text',array(
            'label' =>'wizard.register.label.firstname',
        ));   
    }      

    public function getName()     
    {         
        return 'wizard_user_registation';     
    } 
}
<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wizardalley\UserBundle\Form\Type; 

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseType; 

class ProfileFormType extends BaseType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);       
        $builder->add('facebook','text',array(
                'label' =>'wizard.register.label.facebook',
            ))
            ->add('twitter','text',array(
                'label' =>'wizard.register.label.twitter',
            )
            ); 
    }

    
    public function getName()     
    {         
        return 'wizard_user_profile_edit';     
    } 

    
}

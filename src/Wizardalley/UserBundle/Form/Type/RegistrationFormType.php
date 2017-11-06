<?php
namespace Wizardalley\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class RegistrationFormType
 *
 * @package Wizardalley\UserBundle\Form\Type
 */
class RegistrationFormType extends BaseType
{
    /**
     * @var
     */
    protected $tokenAlpha;

    /**
     * RegistrationFormType constructor.
     *
     * @param string $class \
     */
    public function __construct($class, $tokenAlpha)
    {
        parent::__construct($class);
        $this->tokenAlpha = $tokenAlpha;
    }

    /**
     * @param                           $value
     * @param ExecutionContextInterface $context
     */
    public function validateAlpha($value, ExecutionContextInterface $context)
    {
        $form = $context->getRoot();

        if ($value != $this->tokenAlpha) {
            $context
                ->buildViolation('La code pour accéder a la version alpha n\'est pas valide')
                ->addViolation()
            ;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm(
            $builder,
            $options
        );

        // add your custom field        
        $builder->add(
            'lastname',
            'text',
            [
                'label' => 'wizard.register.label.lastname',
            ]
        )
                ->add(
                    'firstname',
                    'text',
                    [
                        'label' => 'wizard.register.label.firstname',
                    ]
                )
                ->add(
                    'accept_cgv',
                    'checkbox',
                    ['label' => 'wizard.register.field.cgv.accept']
                )
                ->add(
                    'sexe',
                    'choice',
                    [
                        'label'   => 'wizard.register.label.sexe',
                        'choices' => [
                            '0' => 'wizard.register.field.sexe.male',
                            '1' => 'wizard.register.field.sexe.female',
                        ]
                    ]
                )
                ->add(
                    'alpha_code',
                    'text',
                    [
                        'label'       => 'wizard.register.label.token_alpha',
                        'mapped'      => false,
                        'required'    => true,
                        'constraints' => [
                            new NotBlank(),
                            new Callback([$this, 'validateAlpha']),
                        ],
                    ]
                )
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wizard_user_registation';
    }
}
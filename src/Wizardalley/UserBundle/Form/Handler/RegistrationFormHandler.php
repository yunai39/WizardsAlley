<?php
// src/AppBundle/Form/Handler/RegistrationFormHandler.php

namespace Wizardalley\UserBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Wizardalley\CoreBundle\Entity\WizardUser;

/**
 * Class RegistrationFormHandler
 *
 * @package Wizardalley\UserBundle\Form\Handler
 */
class RegistrationFormHandler extends BaseHandler
{
    /** @var */
    protected $appFolder;

    /**
     * RegistrationFormHandler constructor.
     *
     * @param FormInterface           $form
     * @param Request                 $request
     * @param UserManagerInterface    $userManager
     * @param MailerInterface         $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @param                         $appFolder
     */
    public function __construct(FormInterface $form,
        Request $request,
        UserManagerInterface $userManager,
        MailerInterface $mailer,
        TokenGeneratorInterface $tokenGenerator,
        $appFolder
    ) {
        parent::__construct(
            $form,
            $request,
            $userManager,
            $mailer,
            $tokenGenerator
        );
        $this->appFolder = $appFolder;
    }

    protected function onSuccess(UserInterface $user, $confirmation)
    {
        // Note: if you plan on modifying the user then do it before calling the
        // parent method as the parent method will flush the changes
        /** @var $user WizardUser */
        $user->setCreatedAt(new \DateTime());
        $user->setPathProfile('default.png');
        $user->setPathCouverture('dCouverture.png');

        parent::onSuccess($user, $confirmation);

        $fs = new Filesystem();
        // Copier l'image par default

        $fs->copy(
            $this->appFolder . '/../web/uploads/profile/default.png',
            $this->appFolder . '/../web/uploads/profile/' . $user->getId() . '/default.png'
        );
        $fs->copy(
            $this->appFolder . '/../web/uploads/profile/dCouverture.png',
            $this->appFolder . '/../web/uploads/profile/' . $user->getId() . '/dCouverture.png'
        );
    }
}
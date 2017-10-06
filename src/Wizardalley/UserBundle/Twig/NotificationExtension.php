<?php

namespace Wizardalley\UserBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Wizardalley\CoreBundle\Entity\FollowedNotificationRepository;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\WizardUser;

/**
 * Class NotificationExtension
 * @package Wizardalley\UserBundle\Twig
 */
class NotificationExtension extends \Twig_Extension
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * LikePublicationExtension constructor.
     *
     * @param TokenStorage  $tokenStorage
     * @param EntityManager $em
     */
    public function __construct(TokenStorage $tokenStorage, EntityManager $em)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em           = $em;
    }

    /**
     * @return mixed|void
     */
    public function getUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function getImageProfileUser($id) {
        $user = $this->em->getRepository('WizardalleyCoreBundle:WizardUser')->find($id);
        if (!$user instanceof WizardUser) {
            return '';
        }

        return $user->getWebPathProfile();
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function getImageProfilePage($id) {
        /** @var Page $page */
        $page = $this->em->getRepository('WizardalleyCoreBundle:Page')->find($id);
        if (!$page instanceof Page) {
            return '';
        }

        return $page->getWebPathProfile();
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'nb_notification' => new \Twig_Function_Method($this, 'nbNotification'),
            'image_profile_user_id' => new \Twig_Function_Method($this, 'getImageProfileUser'),
            'image_profile_page_id' => new \Twig_Function_Method($this, 'getImageProfilePage'),
        );
    }

    /**
     * @return int
     */
    public function nbNotification()
    {
        $user = $this->getUser();
        if (empty($user)) {
            return 0;
        }
        /** @var FollowedNotificationRepository $repo */
        $repo            = $this->em->getRepository('WizardalleyCoreBundle:FollowedNotification');
        $notifications = $repo->findBy([
            'user'    => $user,
            'checked' => false
        ]);

        return count($notifications);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'notification_extension';
    }
}

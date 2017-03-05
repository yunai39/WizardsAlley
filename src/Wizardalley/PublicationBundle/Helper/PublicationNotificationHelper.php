<?php

namespace Wizardalley\PublicationBundle\Helper;

use Doctrine\ORM\EntityManager;
use Wizardalley\CoreBundle\Entity\FollowedNotification;
use Wizardalley\CoreBundle\Entity\PageUserFollow;
use Wizardalley\CoreBundle\Entity\Publication;

/**
 * Class PublicationNotificationHelper
 * @package Wizardalley\PublicationBundle\Helper
 */
class PublicationNotificationHelper
{
    /** @var EntityManager */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function generateNotificationForPublicationCreated(Publication $publication)
    {
        $page = $publication->getPage();
        // Pour tous les utilisateurs qui suivent la page
        /** @var PageUserFollow $user */
        foreach ($page->getFollowers() as $user) {
            $notification = new FollowedNotification();
            $notification
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setChecked(false)
                ->setUser($user->getUser())
                ->setType('publication')
                ->setDataNotification(json_encode([
                    'page_id'           => $page->getId(),
                    'page_name'         => $page->getName(),
                    'publication_id'    => $publication->getId(),
                    'publication_title' => $publication->getTitle()
                ]));
            $this->em->persist($notification);
        }
        $this->em->flush();
    }
}

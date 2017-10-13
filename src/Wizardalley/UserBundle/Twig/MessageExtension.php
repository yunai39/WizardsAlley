<?php

namespace Wizardalley\UserBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Wizardalley\CoreBundle\Entity\FollowedNotificationRepository;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\WizardUser;

/**
 * Class MessageExtension
 *
 * @package Wizardalley\UserBundle\Twig
 */
class MessageExtension extends \Twig_Extension
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
     * @param TokenStorage $tokenStorage
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
    public function getAllThread()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return [];
        }

        if (!is_object($user = $token->getUser())) {
            return [];
        }

        $query =  $this->em->getRepository('WizardalleyCoreBundle:Thread')->createQueryBuilder('t')
                                ->innerJoin('t.metadata', 'tm')
                                ->innerJoin('tm.participant', 'p')

            // the participant is in the thread participants
                                ->andWhere('p.id = :user_id')
                                ->setParameter('user_id', $user->getId())

            // the thread does not contain spam or flood
                                ->andWhere('t.isSpam = :isSpam')
                                ->setParameter('isSpam', false, \PDO::PARAM_BOOL)

            // the thread is not deleted by this participant
                                ->andWhere('tm.isDeleted = :isDeleted')
                                ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

            // sort by date of last message written by an other participant
                                ->orderBy('tm.lastMessageDate', 'DESC')
        ;

        var_dump($query->getDQL());
        return $query->getQuery()->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            'all_thread' => new \Twig_Function_Method(
                $this,
                'getAllThread'
            ),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'message_extension';
    }
}

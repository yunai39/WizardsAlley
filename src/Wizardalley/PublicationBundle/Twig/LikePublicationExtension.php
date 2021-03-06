<?php

namespace Wizardalley\PublicationBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Wizardalley\CoreBundle\Entity\Publication;
use Wizardalley\CoreBundle\Entity\PublicationUserLike;
use Wizardalley\CoreBundle\Entity\SmallPublicationUserLike;

/**
 * Class LikePublicationExtension
 *
 * @package Wizardalley\PublicationBundle\Twig
 */
class LikePublicationExtension extends \Twig_Extension
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
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            'is_like'       => new \Twig_Function_Method($this, 'isLike'),
            'is_like_small' => new \Twig_Function_Method($this, 'isLikeSmall'),
        ];
    }

    /**
     * @param int $publication
     *
     * @return int
     */
    public function isLike($publication)
    {
        if (empty($this->getUser())) {
            return false;
        }
        $repo            = $this->em->getRepository('WizardalleyCoreBundle:PublicationUserLike');
        $publicationLike = $repo->findOneBy(
            [
                'publication' => $publication,
                'user'        => $this->getUser()->getId()
            ]
        );
        if ($publicationLike instanceof PublicationUserLike) {
            return true;
        }

        return false;
    }

    /**
     * @param int $publication
     *
     * @return int
     */
    public function isLikeSmall($publication)
    {
        if (empty($this->getUser())) {
            return false;
        }
        $repo            = $this->em->getRepository('WizardalleyCoreBundle:SmallPublicationUserLike');
        $publicationLike = $repo->findOneBy(
            [
                'smallPublication' => $publication,
                'user'             => $this->getUser()->getId()
            ]
        );
        if ($publicationLike instanceof SmallPublicationUserLike) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'like_publication_extension';
    }
}

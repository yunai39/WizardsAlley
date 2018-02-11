<?php

namespace Wizardalley\PublicationBundle\Twig;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;

/**
 * Class BlameExtension
 *
 * @package Wizardalley\PublicationBundle\Twig
 */
class BlameExtension extends \Twig_Extension
{
    /** @var EntityManager */
    protected $em;

    /**
     * BlameExtension constructor.
     *
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->em = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return ['blame_count' => new \Twig_Function_Method($this, 'blameCount')];
    }

    /**
     * @param $type
     * @param $id
     *
     * @return string
     */
    public function blameCount($type, $id)
    {
        /** @var Collection $blames */
        $blames =
            $this->em->getRepository('WizardalleyCoreBundle:Blame')->findBy(['type' => $type, 'contentId' => $id]);

        return count($blames);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'blame_extension';
    }
}

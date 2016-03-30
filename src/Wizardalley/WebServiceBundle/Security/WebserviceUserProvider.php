<?php

namespace Wizardalley\WebServiceBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Wizardalley\CoreBundle\Entity\WizardUser as User;
use Doctrine\ORM\EntityManager;

/**
 * Class WebserviceUserProvider
 */
class WebserviceUserProvider implements UserProviderInterface
{
    /** @var EntityManager */
    private $em;

    /**
     * WebserviceUserProvider constructor.
     * @param EntityManager $em
     */
    public function __construct( EntityManager $em)
    {
        $this->em       = $em;
    }

    /**
     * @param string $username
     * @return null|User
     */
    public function loadUserByUsername($username)
    {
        // Do we have a local record?
        if ($user = $this->findUserBy(array('email' => $username))) {
            return $user;
        }

        throw new UsernameNotFoundException(sprintf('No record found for user %s', $username));
    }

    /**
     * @param UserInterface $user
     * @return null|User
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'Acme\DemoBundle\Entity\User';
    }

    /**
     * @param array $criteria
     * @return null|User
     */
    protected function findUserBy(array $criteria)
    {
        $repository = $this->em->getRepository('WizardalleyCoreBundle:WizardUser');
        return $repository->findOneBy($criteria);
    }
}
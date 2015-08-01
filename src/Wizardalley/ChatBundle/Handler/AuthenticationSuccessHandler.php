<?php

namespace Wizardalley\ChatBundle\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Wizardalley\ChatBundle\Entity\UserConnected;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler {

    /**
     * Doctrine
     *
     * @var Doctrine
     */
    protected $doctrine;

    public function __construct(HttpUtils $httpUtils, array $options, $doctrine) {
        parent::__construct($httpUtils, $options);
        $this->doctrine = $doctrine;
    }

    /**
     * @method onAuthenticationFailure
     * 
     * @param Request $request 						The request for the authentification
     * @param TokenInterface $token					The security token
     * 
     * This function will response true if the AuthenticationSuccess was proceded with Ajax
     * Otherwise it will redirect the user toward the a paged based on the role of the user and defined in parameters.yml
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        $user = $token->getUser();
        $entity = new UserConnected();
        $entity->setId($user);
        $entity->setTimeConnected(new \DateTime('now'));
        $entityManager = $this->doctrine->getManager();
        $entityManager->merge($entity);
        $entityManager->flush();
        return parent::onAuthenticationSuccess($request, $token);
    }

}

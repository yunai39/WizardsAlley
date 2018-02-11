<?php
namespace Wizardalley\UserBundle\Redirection;

use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class EntryPointRedirection
 *
 * @package Wizardalley\UserBundle\Redirection
 */
class EntryPointRedirection implements AuthenticationEntryPointInterface
{
    /** @var  Router */
    protected $router;

    /**
     * EntryPointRedirection constructor.
     *
     * @param $router
     */
    public function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * @param Request                      $request
     * @param AuthenticationException|null $authException
     *
     * @return RedirectResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('wizardalley_user_login_redirect'));
    }
}
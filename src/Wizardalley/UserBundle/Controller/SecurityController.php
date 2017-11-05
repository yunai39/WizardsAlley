<?php
namespace Wizardalley\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SecurityController
 *
 * @package Wizardalley\UserBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.context')
                 ->isGranted('IS_AUTHENTICATED_REMEMBERED')
        ) {
            return $this->redirectToRoute('wizardalley_default_homepage');
        }

        // Le service authentication_utils permet de récupérer le nom d'utilisateur
        // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
        // (mauvais mot de passe par exemple)
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render(
            'WizardalleyUserBundle:Security:login.html.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error'         => $authenticationUtils->getLastAuthenticationError(),
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function loginRedirectAction(Request $request)
    {
        $translator = $this->get('translator');
        $request->getSession()
                ->getFlashBag()
                ->add(
                    'login-message',
                    $translator->trans('wizard.error.login')
                )
        ;

        return new RedirectResponse($this->generateUrl('wizardalley_user_login'));
    }
}
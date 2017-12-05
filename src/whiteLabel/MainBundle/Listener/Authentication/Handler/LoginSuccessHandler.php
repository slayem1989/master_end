<?php

namespace whiteLabel\MainBundle\Listener\Authentication\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Class LoginSuccessHandler
 * @package whiteLabel\MainBundle\Listener\Authentication\Handler
 */
class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    protected $router;
    protected $dispatcher;

    /**
     * LoginSuccessHandler constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // URL for redirect the user to where they were before the login process begun if you want.
        // $referer_url = $request->headers->get('referer');

        // On récupère la liste des rôles d'un utilisateur
        $roles = $token->getRoles();

        // On transforme le tableau d'instance en tableau simple
        $rolesTab = array_map(function ($role) {
            return $role->getRole();
        }, $roles);

        if (
            in_array('ROLE_ADMIN', $rolesTab, true) ||
            in_array('ROLE_CLIENT', $rolesTab, true) ||
            in_array('ROLE_COORDINATEUR', $rolesTab, true) ||
            in_array('ROLE_GESTIONNAIRE', $rolesTab, true)
        ) {
            // S'il s'agit d'un admin ou d'un super admin on le redirige vers le backoffice
            $request->getSession()->getFlashBag()->add(
                'danger',
                'Droits insuffisants.'
            );

            $response = new RedirectResponse($this->router->generate('fos_admin_user_security_logout'));
        } elseif (in_array('ROLE_MEMBER', $rolesTab, true)) {
            // sinon, s'il s'agit d'un membre on le redirige vers le frontoffice
            $response = new RedirectResponse($this->router->generate('fo_homepage'));
        } else {
            // sinon il s'agit d'un user
            $request->getSession()->getFlashBag()->add(
                'danger',
                'Identifiants non autorisés.'
            );

            $referer_url = $request->headers->get('referer');
            $response = new RedirectResponse($referer_url);
        }

        return $response;
    }
}

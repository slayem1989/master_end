<?php

namespace whiteLabel\BackOfficeBundle\Listener\Access\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;


/**
 * Class AccessDeniedHandler
 * @package whiteLabel\BackOfficeBundle\Listener\Access\Handler
 */
class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * AccessDeniedHandler constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @param AccessDeniedException $accessDeniedException
     * @return RedirectResponse
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $response = new RedirectResponse($this->router->generate('fos_admin_user_security_logout'));
        return $response;
    }
}

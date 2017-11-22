<?php

namespace whiteLabel\MainBundle\Listener\Authentication\Handler;

use whiteLabel\MainBundle\Entity\User;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class LoginListener
 * @package whiteLabel\MainBundle\Listener\Authentication\Handler
 */
class LoginListener
{
    /**
     * @var \Symfony\Component\Routing\Router
     */
    private $router;

    /**
     * @param \Symfony\Component\Routing\Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    /**
     * @param \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        //$event->stopPropagation();
    }
}

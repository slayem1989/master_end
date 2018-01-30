<?php

namespace whiteLabel\MainBundle\Listener\Exception;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class ExceptionListener
 * @package whiteLabel\MainBundle\Listener\Exception
 */
class ExceptionListener
{

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ContainerInterface
     */
    private $container;


    /**
     * ExceptionListener constructor.
     * @param Router $router
     * @param ContainerInterface $container
     */
    public function __construct(Router $router, ContainerInterface $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {

        $session = $event->getRequest()->getSession();
        $nomDomaine_current = $_SERVER["SERVER_NAME"];

        // You get the exception object from the received event
        $exception = $event->getException();
        $className = get_class($exception);
        $array = explode('\\', $className);
        $basename = end($array);

        switch ($basename) {

            case 'AccessDeniedHttpException':
                $message = 'Accès restreint';
                break;

            case 'NotFoundHttpException':
                $message = 'Page non trouvée';
                break;

            case 'FatalErrorException':
            case 'FatalThrowableError':
                $message = 'Erreur interne du serveur';
                break;

            case 'ServiceUnavailableHttpException':
                $message = 'Service indisponible pour le moment';
                break;

            default:
                $message = 'Une erreur est survenue';
                break;
        }

        $session->getFlashBag()->add('danger', $message);
        if ($this->container->getParameter('url_bo') == $nomDomaine_current) {
            $url = $this->router->generate('bo_dashboard');
        } elseif ($this->container->getParameter('url_fo') == $nomDomaine_current) {
            $url = $this->router->generate('fo_homepage');
        } else {
            $url = $this->router->generate('fos_user_security_login');
        }
        $response = new RedirectResponse($url);
        //$event->setResponse($response);
    }
}

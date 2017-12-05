<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CoreController
 * @package whiteLabel\BackOfficeBundle\Controller
 */
class CoreController extends Controller
{
    /**
     * @param $_route_params
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function layoutHeaderAction($_route_params)
    {
        $EM = $this->getDoctrine()->getManager();

        $username = "";
        $user = $this->getUser();

        if (null !== $user) {
            $username = $user;
        }
        $_SESSION['login'] = $username;

        /* /////////////////////////////////////////////////////////////////
                                    GET CLIENT
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:Client_');
        $client = $repo->find($_route_params['clientId']);

        return $this->render('whiteLabelBackOfficeBundle:Core:header.html.twig', array(
            'clientLogoUrl' => $client->getClientInformation()->getLogoUrl()
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function layoutHeaderAccueilAction()
    {
        $username = "";
        $user = $this->getUser();

        if (null !== $user) {
            $username = $user;
        }
        $_SESSION['login'] = $username;

        return $this->render('whiteLabelBackOfficeBundle:Core:header_accueil.html.twig', array());
    }
}

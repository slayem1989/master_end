<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DashboardController
 * @package whiteLabel\BackOfficeBundle\Controller
 */
class DashboardController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LIST OF CLIENT
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:Client_');
        $list = $repo->findBy(
            array(),
            array('id' => 'DESC')
        );

        return $this->render('whiteLabelBackOfficeBundle:Dashboard:index.html.twig', array(
            'list' => $list
        ));
    }
}

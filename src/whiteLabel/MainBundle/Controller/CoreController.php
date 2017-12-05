<?php

namespace whiteLabel\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CoreController
 * @package whiteLabel\MainBundle\Controller
 */
class CoreController extends Controller
{
    /**
     * Display layout's header
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function layoutHeaderAction()
    {
        return $this->render('whiteLabelMainBundle:Core:header.html.twig', array());
    }
}

<?php

namespace whiteLabel\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HomepageController
 * @package whiteLabel\FrontOfficeBundle\Controller
 */
class HomepageController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('whiteLabelFrontOfficeBundle:Homepage:index.html.twig');
    }
}

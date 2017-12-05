<?php

namespace blackLabel\HistoriqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HomepageController
 * @package blackLabel\HistoriqueBundle\Controller
 */
class HomepageController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('blackLabelHistoriqueBundle:Homepage:index.html.twig');
    }
}

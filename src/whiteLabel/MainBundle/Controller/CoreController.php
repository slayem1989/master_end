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
        $nomDomaine_current = $_SERVER["SERVER_NAME"];
        $nomDomaine_fo = $this->getParameter('url_fo');
        $nomDomaine_bo = $this->getParameter('url_bo');

        $title = '';
        if ($nomDomaine_fo == $nomDomaine_current) {
            $title = 'fo';
        } elseif ($nomDomaine_bo == $nomDomaine_current) {
            $title = 'bo';
        } else {
            $title = 'bo';
        }

        return $this->render('whiteLabelMainBundle:Core:header.html.twig', array(
            'title' => $title
        ));
    }

    /**
     * Display layout's footer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function layoutFooterAction()
    {
        return $this->render('whiteLabelMainBundle:Core:footer.html.twig');
    }
}
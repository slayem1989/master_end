<?php

namespace whiteLabel\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HomepageController
 * @package whiteLabel\WebBundle\Controller
 */
class HomepageController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $nomDomaine_current = $_SERVER["SERVER_NAME"];
        $nomDomaine_fo = $this->getParameter('url_fo');
        $nomDomaine_bo = $this->getParameter('url_bo');

        if ($nomDomaine_fo == $nomDomaine_current) {
            return $this->redirectToRoute('fos_user_security_login', array());
        } elseif ($nomDomaine_bo == $nomDomaine_current) {
            return $this->redirectToRoute('fos_admin_user_security_login', array());
        } else {
            return $this->redirectToRoute('fos_admin_user_security_login', array());
        }
    }
}

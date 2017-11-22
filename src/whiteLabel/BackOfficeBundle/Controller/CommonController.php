<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CommonController
 * @package whiteLabel\BackOfficeBundle\Controller
 */
class CommonController extends Controller
{
    /**
     * Display layout's header
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function layoutHeaderAction()
    {
        $user = $this->getUser();
        if (null !== $user) {
            $username = $user;
        } else {
            $username = "";
        }

        $_SESSION['login'] = $username;

        return $this->render('whiteLabelBackOfficeBundle:Common:header.html.twig', array());
    }

    /**
     * Display layout's footer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function layoutFooterAction()
    {
        return $this->render('whiteLabelBackOfficeBundle:Common:footer.html.twig');
    }
}

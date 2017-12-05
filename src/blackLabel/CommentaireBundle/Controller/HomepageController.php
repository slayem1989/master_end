<?php

namespace blackLabel\CommentaireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HomepageController
 * @package blackLabel\CommentaireBundle\Controller
 */
class HomepageController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('blackLabelCommentaireBundle:Homepage:index.html.twig');
    }
}

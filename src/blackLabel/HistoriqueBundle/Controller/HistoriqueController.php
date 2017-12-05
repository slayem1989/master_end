<?php

namespace blackLabel\HistoriqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HistoriqueController
 * @package blackLabel\HistoriqueBundle\Controller
 */
class HistoriqueController extends Controller
{
    /**
     * @param $clientId
     * @param $lotId
     * @return Response
     */
    public function listAction($clientId, $lotId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LIST OF HISTORIQUE
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelHistoriqueBundle:Historique');
        $list = $repo->findBy(array(
            'lot_id' => $lotId
        ));

        return $this->render('blackLabelHistoriqueBundle:Historique:list.html.twig', array(
            'list'      => $list,
            'clientId'  => $clientId,
            'lotId'     => $lotId
        ));
    }
}

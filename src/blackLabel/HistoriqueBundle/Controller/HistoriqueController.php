<?php

namespace blackLabel\HistoriqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
    public function listLotAction($clientId, $lotId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LOT
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_lot');
        $lot = $repo->find($lotId);

        /* /////////////////////////////////////////////////////////////////
                                GET LIST OF HISTORIQUE
        ///////////////////////////////////////////////////////////////// */
        $repo_historique = $EM->getRepository('blackLabelHistoriqueBundle:Historique_lot');
        $list = $repo_historique->findBy(array(
            'lotId' => $lotId
        ));

        return $this->render('blackLabelHistoriqueBundle:Historique:list_lot.html.twig', array(
            'list'      => $list,
            'clientId'  => $clientId,
            'lotNumero' => $lot->getNumero()
        ));
    }

    /**
     * @param $clientId
     * @param $primeId
     * @return Response
     */
    public function listPrimeAction($clientId, $primeId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET PRIME
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_prime');
        $prime = $repo->find($primeId);

        /* /////////////////////////////////////////////////////////////////
                                GET LIST OF HISTORIQUE
        ///////////////////////////////////////////////////////////////// */
        $repo_historique = $EM->getRepository('blackLabelHistoriqueBundle:Historique_prime');
        $list = $repo_historique->findBy(array(
            'primeId' => $primeId
        ));

        return $this->render('blackLabelHistoriqueBundle:Historique:list_prime.html.twig', array(
            'list'      => $list,
            'clientId'  => $clientId,
            'prime'     => $prime
        ));
    }
}

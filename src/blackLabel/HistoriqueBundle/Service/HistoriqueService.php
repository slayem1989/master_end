<?php

namespace blackLabel\HistoriqueBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use blackLabel\HistoriqueBundle\Entity\Historique_lot;

/**
 * Class HistoriqueService
 * @package blackLabel\HistoriqueBundle\Service
 */
class HistoriqueService
{
    /**
     * @var null
     */
    private $EM = null;

    /**
     * @var Container
     */
    private $container;



    /**
     * HistoriqueService constructor.
     * @param $doctrine
     * @param Container $container
     */
    public function __construct($doctrine, Container $container)
    {
        $this->EM = $doctrine->getManager();
        $this->container = $container;
    }



    /* *******************************************************************
     * *******************************************************************
                            F I N D / S E A R C H
     * *******************************************************************
     * ***************************************************************** */
    /**
     * @param $lotId
     * @param $action
     * @param $content
     * @param $statutId
     * @param null $dateForm
     */
    public function saveLot(
        $lotId,
        $action,
        $content,
        $statutId,
        $dateForm = null
    ) {
        $repo_statut = $this->EM->getRepository('whiteLabelBackOfficeBundle:Statut_lot');
        $statutSlug = $repo_statut->findSlugByStatut($statutId);

        if ($dateForm) $etatCommande = $statutSlug . ' et indiqué au ' . $dateForm;
        else $etatCommande = $statutSlug;

        $historique = new Historique_lot();
        $historique->setLotId($lotId);
        $historique->setAction($action);
        $historique->setContent($content);
        $historique->setStatutId($statutId);
        $historique->setStatutSlug($etatCommande);

        $this->EM->persist($historique);
        $this->EM->flush();
    }


    /* *******************************************************************
     * *******************************************************************
                            F U N C T I O N S
     * *******************************************************************
     * ***************************************************************** */
}

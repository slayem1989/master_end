<?php

namespace blackLabel\HistoriqueBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use blackLabel\HistoriqueBundle\Entity\Historique_lot;
use blackLabel\HistoriqueBundle\Entity\Historique_prime;

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
     * @return Historique_lot
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

        if ($dateForm) $statutCurrent = $statutSlug . ' et indiqué au ' . $dateForm;
        else $statutCurrent = $statutSlug;

        $historique = new Historique_lot();
        $historique->setLotId($lotId);
        $historique->setAction($action);
        $historique->setContent($content);
        $historique->setStatutId($statutId);
        $historique->setStatutSlug($statutCurrent);

        return $historique;
    }

    /**
     * @param $primeId
     * @param $action
     * @param $content
     * @param $statutId
     * @return Historique_prime
     */
    public function savePrime(
        $primeId,
        $action,
        $content,
        $statutId
    ) {
        $repo_statut = $this->EM->getRepository('whiteLabelBackOfficeBundle:Statut_prime');
        $statutSlug = $repo_statut->findSlugByStatut($statutId);

        $historique = new Historique_prime();
        $historique->setPrimeId($primeId);
        $historique->setAction($action);
        $historique->setContent($content);
        $historique->setStatutId($statutId);
        $historique->setStatutSlug($statutSlug);

        return $historique;
    }

    /* *******************************************************************
     * *******************************************************************
                            F U N C T I O N S
     * *******************************************************************
     * ***************************************************************** */
}

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
     */
    public function initLot(
        $lotId,
        $action,
        $content,
        $statutId,
        $dateForm = null
    ) {
        $repo_statut = $this->EM->getRepository('whiteLabelBackOfficeBundle:Statut_lot');
        $statutSlug = $repo_statut->findSlugByStatut($statutId);

        if ($dateForm) $state = $statutSlug . ' et indiqué au ' . $dateForm;
        else $state = $statutSlug;

        $historique = new Historique_lot();
        $historique->setLotId($lotId);
        $historique->setAction($action);
        $historique->setContent($content);
        $historique->setStatutId($statutId);
        $historique->setStatutSlug($state);

        // No need to move FLUSH() because there is always only ONE Lot
        $this->EM->persist($historique);
        $this->EM->flush();
        $this->EM->clear();
    }

    /**
     * @param $lotId
     * @param $action
     * @param $content
     * @param $statutId
     * @return int
     */
    public function saveLotByCommentaire(
        $lotId,
        $action,
        $content,
        $statutId
    ) {
        $repo_statut = $this->EM->getRepository('whiteLabelBackOfficeBundle:Statut_prime');
        $statutSlug = $repo_statut->findSlugByStatut($statutId);

        $historique = new Historique_lot();
        $historique->setLotId($lotId);
        $historique->setAction($action);
        $historique->setContent($content);
        $historique->setStatutId($statutId);
        $historique->setStatutSlug($statutSlug);

        $this->EM->persist($historique);
        $this->EM->flush();
        $this->EM->clear();

        return $historique->getId();
    }

    /**
     * @param $primeId
     * @param $action
     * @param $content
     * @param $statutId
     */
    public function initPrime(
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

        // Move FLUSH() to avoid memory limit in a loop
        $this->EM->persist($historique);
    }

    /**
     * @param $primeId
     * @param $action
     * @param $content
     * @param $statutId
     * @return int
     */
    public function savePrimeByCommentaire(
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

        $this->EM->persist($historique);
        $this->EM->flush();
        $this->EM->clear();

        return $historique->getId();
    }


    /* *******************************************************************
     * *******************************************************************
                            F U N C T I O N S
     * *******************************************************************
     * ***************************************************************** */
}

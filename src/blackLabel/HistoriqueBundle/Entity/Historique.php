<?php

namespace blackLabel\HistoriqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use blackLabel\GenericBundle\Entity\Log;

/**
 * Historique
 *
 * @ORM\Table(name="historique", indexes={
 *      @ORM\Index(name="lot_idx", columns={"lot_id"}),
 *      @ORM\Index(name="statut_idx", columns={"statut_id"})
 * })
 * @ORM\Entity(repositoryClass="blackLabel\HistoriqueBundle\Repository\HistoriqueRepository")
 */
class Historique extends Log
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="lot_id", type="integer")
     */
    private $lot_id;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;

    /**
     * @var array
     *
     * @ORM\Column(name="content", type="array", nullable=true)
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="statut_id", type="integer")
     */
    private $statut_id;

    /**
     * @var string
     *
     * @ORM\Column(name="statut_slug", type="string", length=255)
     */
    private $statutSlug;



    const STATUT_1 = 'Intégration d’un nouveau lot de prime';
    const STATUT_2 = 'Emission de la ND par le groupe Up';
    const STATUT_3 = 'Emission des BAT par le groupe Up';
    const STATUT_4 = 'Validation de la ND par TMF';
    const STATUT_44 = 'Refus de la ND par TMF';
    const STATUT_5 = 'Validation des BAT par TMF';
    const STATUT_55 = 'Refus des BAT par TMF';
    const STATUT_6 = 'Réception des fonds par le groupe Up si solution 1 ou par TMF si solution 2';
    const STATUT_7 = 'Edition des LC';
    const STATUT_8 = 'Expédition des LC / Virement';



    /**
     * Historique constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lotId
     *
     * @param integer $lotId
     *
     * @return Historique
     */
    public function setLotId($lotId)
    {
        $this->lot_id = $lotId;

        return $this;
    }

    /**
     * Get lotId
     *
     * @return integer
     */
    public function getLotId()
    {
        return $this->lot_id;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return Historique
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set content
     *
     * @param array $content
     *
     * @return Historique
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set statutId
     *
     * @param integer $statutId
     *
     * @return Historique
     */
    public function setStatutId($statutId)
    {
        $this->statut_id = $statutId;

        return $this;
    }

    /**
     * Get statutId
     *
     * @return integer
     */
    public function getStatutId()
    {
        return $this->statut_id;
    }

    /**
     * Set statutSlug
     *
     * @param string $statutSlug
     *
     * @return Historique
     */
    public function setStatutSlug($statutSlug)
    {
        $this->statutSlug = $statutSlug;

        return $this;
    }

    /**
     * Get statutSlug
     *
     * @return string
     */
    public function getStatutSlug()
    {
        return $this->statutSlug;
    }
}

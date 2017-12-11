<?php

namespace blackLabel\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use blackLabel\GenericBundle\Entity\Log;

/**
 * Import_canal
 *
 * @ORM\Table(name="import_canal", indexes={
 *      @ORM\Index(name="lot_idx", columns={"lot_id"})
 * })
 * @ORM\Entity(repositoryClass="blackLabel\ImportBundle\Repository\Import_canalRepository")
 */
class Import_canal extends Log
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="lot_id", type="integer")
     */
    private $lot_id;

    /**
     * @var int
     *
     * @ORM\Column(name="nombre_commande", type="integer")
     */
    private $nombre_commande;

    /**
     * @var string
     *
     * @ORM\Column(name="somme", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $somme;

    /**
     * @var string
     *
     * @ORM\Column(name="moyenne", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $moyenne;

    /**
     * @var string
     *
     * @ORM\Column(name="max", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $max;

    /**
     * @var string
     *
     * @ORM\Column(name="min", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $min;

    /**
     * @var string
     *
     * @ORM\Column(name="ecart_type", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $ecart_type;



    /**
     * Import_canal constructor.
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
     * Set title
     *
     * @param string $title
     *
     * @return Import_canal
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set lotId
     *
     * @param integer $lotId
     *
     * @return Import_canal
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
     * Set nombreCommande
     *
     * @param integer $nombreCommande
     *
     * @return Import_canal
     */
    public function setNombreCommande($nombreCommande)
    {
        $this->nombre_commande = $nombreCommande;

        return $this;
    }

    /**
     * Get nombreCommande
     *
     * @return integer
     */
    public function getNombreCommande()
    {
        return $this->nombre_commande;
    }

    /**
     * Set somme
     *
     * @param string $somme
     *
     * @return Import_canal
     */
    public function setSomme($somme)
    {
        $this->somme = $somme;

        return $this;
    }

    /**
     * Get somme
     *
     * @return string
     */
    public function getSomme()
    {
        return $this->somme;
    }

    /**
     * Set moyenne
     *
     * @param string $moyenne
     *
     * @return Import_canal
     */
    public function setMoyenne($moyenne)
    {
        $this->moyenne = $moyenne;

        return $this;
    }

    /**
     * Get moyenne
     *
     * @return string
     */
    public function getMoyenne()
    {
        return $this->moyenne;
    }

    /**
     * Set max
     *
     * @param string $max
     *
     * @return Import_canal
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get max
     *
     * @return string
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set min
     *
     * @param string $min
     *
     * @return Import_canal
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min
     *
     * @return string
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set ecartType
     *
     * @param string $ecartType
     *
     * @return Import_canal
     */
    public function setEcartType($ecartType)
    {
        $this->ecart_type = $ecartType;

        return $this;
    }

    /**
     * Get ecartType
     *
     * @return string
     */
    public function getEcartType()
    {
        return $this->ecart_type;
    }
}

<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use blackLabel\GenericBundle\Entity\Log;

/**
 * Cheque_item
 *
 * @ORM\Table(name="cheque_item")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Cheque_itemRepository")
 */
class Cheque_item extends Log
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
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var \whiteLabel\BackOfficeBundle\Entity\Cheque_stock
     *
     * @ORM\ManyToOne(targetEntity="whiteLabel\BackOfficeBundle\Entity\Cheque_stock", inversedBy="cheque")
     */
    private $stock;



    /**
     * Cheque_item constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }



    /**
     * @return string
     */
    public function __toString() {
        return (string)$this->id;
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
     * Set numero
     *
     * @param integer $numero
     *
     * @return Cheque_item
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set stock
     *
     * @param \whiteLabel\BackOfficeBundle\Entity\Cheque_stock $stock
     *
     * @return Cheque_item
     */
    public function setStock(\whiteLabel\BackOfficeBundle\Entity\Cheque_stock $stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return \whiteLabel\BackOfficeBundle\Entity\Cheque_stock
     */
    public function getStock()
    {
        return $this->stock;
    }
}

<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use blackLabel\GenericBundle\Entity\Log;

/**
 * Cheque_stock
 *
 * @ORM\Table(name="cheque_stock", indexes={
 *      @ORM\Index(name="client_idx", columns={"client_id"}),
 *      @ORM\Index(name="banque_idx", columns={"banque_id"})
 * })
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Cheque_stockRepository")
 */
class Cheque_stock extends Log
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
     * @ORM\Column(name="client_id", type="integer")
     */
    private $clientId;

    /**
     * @var int
     *
     * @ORM\Column(name="banque_id", type="integer")
     */
    private $banqueId;

    /**
     * @var string
     *
     * @ORM\Column(name="reference_boite", type="string", length=255)
     */
    private $referenceBoite;

    /**
     * @var string
     *
     * @ORM\Column(name="first", type="string", length=7)
     */
    private $first;

    /**
     * @var string
     *
     * @ORM\Column(name="last", type="string", length=7)
     */
    private $last;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reception", type="datetime")
     */
    private $dateReception;

    /**
     * @var \whiteLabel\BackOfficeBundle\Entity\Cheque_item
     *
     * @ORM\OneToMany(targetEntity="whiteLabel\BackOfficeBundle\Entity\Cheque_item", mappedBy="stock", cascade={"persist"})
     */
    private $cheque;



    /**
     * Cheque_stock constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->cheque = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set clientId
     *
     * @param integer $clientId
     *
     * @return Cheque_stock
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return integer
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set banqueId
     *
     * @param integer $banqueId
     *
     * @return Cheque_stock
     */
    public function setBanqueId($banqueId)
    {
        $this->banqueId = $banqueId;

        return $this;
    }

    /**
     * Get banqueId
     *
     * @return integer
     */
    public function getBanqueId()
    {
        return $this->banqueId;
    }

    /**
     * Set referenceBoite
     *
     * @param string $referenceBoite
     *
     * @return Cheque_stock
     */
    public function setReferenceBoite($referenceBoite)
    {
        $this->referenceBoite = $referenceBoite;

        return $this;
    }

    /**
     * Get referenceBoite
     *
     * @return string
     */
    public function getReferenceBoite()
    {
        return $this->referenceBoite;
    }

    /**
     * Set first
     *
     * @param string $first
     *
     * @return Cheque_stock
     */
    public function setFirst($first)
    {
        $this->first = $first;

        return $this;
    }

    /**
     * Get first
     *
     * @return string
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * Set last
     *
     * @param string $last
     *
     * @return Cheque_stock
     */
    public function setLast($last)
    {
        $this->last = $last;

        return $this;
    }

    /**
     * Get last
     *
     * @return string
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return Cheque_stock
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set dateReception
     *
     * @param \DateTime $dateReception
     *
     * @return Cheque_stock
     */
    public function setDateReception($dateReception)
    {
        $this->dateReception = $dateReception;

        return $this;
    }

    /**
     * Get dateReception
     *
     * @return \DateTime
     */
    public function getDateReception()
    {
        return $this->dateReception;
    }

    /**
     * Add cheque
     *
     * @param \whiteLabel\BackOfficeBundle\Entity\Cheque_item $cheque
     *
     * @return Cheque_stock
     */
    public function addCheque(\whiteLabel\BackOfficeBundle\Entity\Cheque_item $cheque)
    {
        $this->cheque[] = $cheque;

        return $this;
    }

    /**
     * Remove cheque
     *
     * @param \whiteLabel\BackOfficeBundle\Entity\Cheque_item $cheque
     */
    public function removeCheque(\whiteLabel\BackOfficeBundle\Entity\Cheque_item $cheque)
    {
        $this->cheque->removeElement($cheque);
    }

    /**
     * Get cheque
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCheque()
    {
        return $this->cheque;
    }
}

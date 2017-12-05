<?php

namespace whiteLabel\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client_banque
 *
 * @ORM\Table(name="client_banque")
 * @ORM\Entity(repositoryClass="whiteLabel\BackOfficeBundle\Repository\Client_banqueRepository")
 */
class Client_banque
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="rib", type="string", length=255, nullable=true)
     */
    private $rib;

    /**
     * @var string
     *
     * @ORM\Column(name="iban", type="string", length=255, nullable=true)
     */
    private $iban;

    /**
     * @var string
     *
     * @ORM\Column(name="bic", type="string", length=255, nullable=true)
     */
    private $bic;

    /**
     * @var string
     *
     * @ORM\Column(name="titulaire", type="string", length=255, nullable=true)
     */
    private $titulaire;

    /**
     * @var \whiteLabel\BackOfficeBundle\Entity\Client_banque
     *
     * @ORM\ManyToOne(targetEntity="whiteLabel\BackOfficeBundle\Entity\Client_", inversedBy="banque")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Client_banque
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set rib
     *
     * @param string $rib
     *
     * @return Client_banque
     */
    public function setRib($rib)
    {
        $this->rib = $rib;

        return $this;
    }

    /**
     * Get rib
     *
     * @return string
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * Set iban
     *
     * @param string $iban
     *
     * @return Client_banque
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban
     *
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set bic
     *
     * @param string $bic
     *
     * @return Client_banque
     */
    public function setBic($bic)
    {
        $this->bic = $bic;

        return $this;
    }

    /**
     * Get bic
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Set titulaire
     *
     * @param string $titulaire
     *
     * @return Client_banque
     */
    public function setTitulaire($titulaire)
    {
        $this->titulaire = $titulaire;

        return $this;
    }

    /**
     * Get titulaire
     *
     * @return string
     */
    public function getTitulaire()
    {
        return $this->titulaire;
    }

    /**
     * Set client
     *
     * @param \whiteLabel\BackOfficeBundle\Entity\Client_ $client
     *
     * @return Client_banque
     */
    public function setClient(\whiteLabel\BackOfficeBundle\Entity\Client_ $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \whiteLabel\BackOfficeBundle\Entity\Client_
     */
    public function getClient()
    {
        return $this->client;
    }
}
